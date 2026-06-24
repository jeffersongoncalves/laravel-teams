<?php

namespace JeffersonGoncalves\Teams\Concerns;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use JeffersonGoncalves\Teams\Models\Team;
use JeffersonGoncalves\Teams\Teams;

/**
 * Adds Teams support to the consuming User model.
 *
 * The model should provide a `current_team_id` column.
 *
 * @property int|null $current_team_id
 * @property-read Team|null $currentTeam
 * @property-read Collection<int, Team> $ownedTeams
 * @property-read Collection<int, Team> $teams
 *
 * @mixin Model
 */
trait HasTeams
{
    public static function bootHasTeams(): void
    {
        static::created(function (Model $user): void {
            if (! config('teams.personal_teams', true)) {
                return;
            }

            $name = explode(' ', (string) $user->getAttribute('name'), 2)[0];

            Teams::teamModel()::forceCreate([
                'user_id' => $user->getKey(),
                'name' => $name."'s Team",
                'personal_team' => true,
            ]);
        });
    }

    public function ownedTeams(): HasMany
    {
        return $this->hasMany(Teams::teamModel());
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Teams::teamModel(), Teams::membershipModel())
            ->withTimestamps()
            ->as('membership');
    }

    public function currentTeam(): BelongsTo
    {
        if (is_null($this->getAttribute('current_team_id')) && $this->getKey()) {
            $this->switchTeam($this->personalTeam());
        }

        return $this->belongsTo(Teams::teamModel(), 'current_team_id');
    }

    public function personalTeam(): ?Team
    {
        return $this->ownedTeams->where('personal_team', true)->first();
    }

    public function switchTeam($team): bool
    {
        if (is_null($team) || ! $this->belongsToTeam($team)) {
            return false;
        }

        $this->forceFill([
            'current_team_id' => $team->id,
        ])->save();

        $this->setRelation('currentTeam', $team);

        return true;
    }

    public function belongsToTeam($team): bool
    {
        if (is_null($team)) {
            return false;
        }

        return $this->ownsTeam($team) || $this->teams->contains(fn ($t): bool => $t->id === $team->id);
    }

    public function ownsTeam($team): bool
    {
        if (is_null($team)) {
            return false;
        }

        return $this->getKey() == $team->user_id;
    }
}
