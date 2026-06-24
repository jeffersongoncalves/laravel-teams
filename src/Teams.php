<?php

namespace JeffersonGoncalves\Teams;

use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\Teams\Models\Membership;
use JeffersonGoncalves\Teams\Models\Team;
use JeffersonGoncalves\Teams\Models\TeamInvitation;

class Teams
{
    /**
     * @return class-string<Team>
     */
    public static function teamModel(): string
    {
        return config('teams.models.team', Team::class);
    }

    /**
     * @return class-string<TeamInvitation>
     */
    public static function teamInvitationModel(): string
    {
        return config('teams.models.team_invitation', TeamInvitation::class);
    }

    /**
     * @return class-string<Membership>
     */
    public static function membershipModel(): string
    {
        return config('teams.models.membership', Membership::class);
    }

    /**
     * @return class-string<Model>
     */
    public static function userModel(): string
    {
        return config('teams.user_model', 'App\\Models\\User');
    }

    public static function guard(): string
    {
        return config('teams.guard', 'web');
    }

    public static function newTeamModel(): Team
    {
        $class = static::teamModel();

        return new $class;
    }
}
