<?php

namespace JeffersonGoncalves\Teams;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TeamsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'teams';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasMigrations([
                'create_teams_table',
                'create_team_memberships_table',
                'create_team_invitations_table',
                'add_current_team_id_to_users_table',
            ]);
    }
}
