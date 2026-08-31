<?php

namespace JeffersonGoncalves\Teams\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use JeffersonGoncalves\Teams\TeamsServiceProvider;
use JeffersonGoncalves\Teams\Tests\Fixtures\User;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function getPackageProviders($app): array
    {
        return [
            TeamsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', $this->testing_connection());

        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('teams.user_model', User::class);
    }

    /**
     * Defaults to an in-memory SQLite connection for local development; CI
     * (tests.yml) sets TEAMS_TEST_DB_* to run the same suite against real
     * MySQL and PostgreSQL instances too. Deliberately not the plain DB_*
     * names: Orchestra Testbench itself sets DB_CONNECTION=testing by
     * convention, which would collide with (and always win over) a driver
     * value read from the same variable here.
     *
     * @return array<string, mixed>
     */
    protected function testing_connection(): array
    {
        $driver = env('TEAMS_TEST_DB_DRIVER', 'sqlite');

        if ($driver === 'sqlite') {
            return ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => ''];
        }

        return [
            'driver' => $driver,
            'host' => env('TEAMS_TEST_DB_HOST', '127.0.0.1'),
            'port' => env('TEAMS_TEST_DB_PORT'),
            'database' => env('TEAMS_TEST_DB_DATABASE', 'testing'),
            'username' => env('TEAMS_TEST_DB_USERNAME', 'root'),
            'password' => env('TEAMS_TEST_DB_PASSWORD', ''),
            'charset' => $driver === 'pgsql' ? 'utf8' : 'utf8mb4',
            'prefix' => '',
        ];
    }

    /**
     * Same order as TeamsServiceProvider::hasMigrations(), preceded by a
     * users fixture table (the package expects the host app to already
     * have one). Hand-rolled Schema::create() calls without RefreshDatabase
     * worked on SQLite's fresh in-memory database per test, but failed with
     * "table already exists" from the second test onward against a
     * persistent MySQL/Postgres database.
     */
    private const MIGRATION_ORDER = [
        'create_teams_table',
        'create_team_memberships_table',
        'create_team_invitations_table',
        'add_current_team_id_to_users_table',
    ];

    protected function defineDatabaseMigrations(): void
    {
        $stubsPath = __DIR__.'/../database/migrations';
        $tempPath = sys_get_temp_dir().'/laravel-teams-migrations';

        if (! is_dir($tempPath)) {
            mkdir($tempPath, 0755, true);
        }

        $usersFixture = __DIR__.'/Fixtures/create_users_table.php.stub';
        $usersTarget = $tempPath.'/000_create_users_table.php';
        if (file_exists($usersFixture) && ! file_exists($usersTarget)) {
            copy($usersFixture, $usersTarget);
        }

        foreach (self::MIGRATION_ORDER as $index => $name) {
            copy($stubsPath.'/'.$name.'.php', $tempPath.'/'.sprintf('%03d_%s.php', $index + 1, $name));
        }

        $this->loadMigrationsFrom($tempPath);
    }
}
