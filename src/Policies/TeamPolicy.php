<?php

namespace JeffersonGoncalves\Teams\Policies;

use Illuminate\Contracts\Auth\Authenticatable;
use JeffersonGoncalves\Teams\Models\Team;

class TeamPolicy
{
    public function viewAny(Authenticatable $authenticatable): bool
    {
        return true;
    }

    public function view(Authenticatable $authenticatable, Team $team): bool
    {
        return true;
    }

    public function create(Authenticatable $authenticatable): bool
    {
        return true;
    }

    public function update(Authenticatable $authenticatable, Team $team): bool
    {
        return $team->user_id === (int) $authenticatable->getAuthIdentifier();
    }

    public function delete(Authenticatable $authenticatable, Team $team): bool
    {
        return $team->personal_team === false;
    }

    public function restore(Authenticatable $authenticatable, Team $team): bool
    {
        return false;
    }

    public function forceDelete(Authenticatable $authenticatable, Team $team): bool
    {
        return false;
    }
}
