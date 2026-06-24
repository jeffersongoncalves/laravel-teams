<?php

use JeffersonGoncalves\Teams\Models\Membership;
use JeffersonGoncalves\Teams\Models\Team;
use JeffersonGoncalves\Teams\Models\TeamInvitation;
use JeffersonGoncalves\Teams\Teams;
use JeffersonGoncalves\Teams\TeamsServiceProvider;

it('registers the service provider', function () {
    expect(app()->getProviders(TeamsServiceProvider::class))->not->toBeEmpty();
});

it('loads the package configuration', function () {
    expect(config('teams.guard'))->toBe('web')
        ->and(config('teams.tables.teams'))->toBe('teams');
});

it('resolves the configured models', function () {
    expect(Teams::teamModel())->toBe(Team::class)
        ->and(Teams::teamInvitationModel())->toBe(TeamInvitation::class)
        ->and(Teams::membershipModel())->toBe(Membership::class);
});
