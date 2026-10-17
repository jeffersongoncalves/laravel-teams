<?php

namespace JeffersonGoncalves\Teams\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use JeffersonGoncalves\PestPluginDatabaseMigrations\LoadsPackageMigrations;
use JeffersonGoncalves\Teams\TeamsServiceProvider;
use JeffersonGoncalves\Teams\Tests\Fixtures\User;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    use LoadsPackageMigrations;
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
     * The package expects the host app to already have a users table: the fixture's
     * 0000_00_00_000000_ name sorts it before the package migrations (staged as 0000_…,
     * 0001_… in TeamsServiceProvider::hasMigrations() order).
     */
    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/Fixtures/migrations');
        $this->loadPackageMigrations(TeamsServiceProvider::class);
    }
}
