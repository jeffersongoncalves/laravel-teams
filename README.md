# Laravel Teams

[![Buy Me A Coffee](https://img.shields.io/badge/Buy%20Me%20A%20Coffee-support-FFDD00?style=flat-square&logo=buy-me-a-coffee&logoColor=black)](https://buymeacoffee.com/jeffersongoncalves)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-teams.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-teams)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-teams/tests.yml?branch=master&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/laravel-teams/actions?query=workflow%3ATests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-teams/fix-php-code-style-issues.yml?branch=master&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/laravel-teams/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-teams.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-teams)

Framework-agnostic Teams core for Laravel: Eloquent models, memberships, team invitations, a `HasTeams` user trait, a team policy, and configurable models/tables. This is the foundation package consumed by [`jeffersongoncalves/filament-teams`](https://github.com/jeffersongoncalves/filament-teams).

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/laravel-teams
```

Publish and run the migrations:

```bash
php artisan vendor:publish --tag="teams-migrations"
php artisan migrate
```

Optionally publish the config file:

```bash
php artisan vendor:publish --tag="teams-config"
```

## Usage

Add the `HasTeams` trait to your `User` model:

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use JeffersonGoncalves\Teams\Concerns\HasTeams;

class User extends Authenticatable
{
    use HasTeams;
}
```

Make sure your `users` table has a nullable `current_team_id` column (the published
migration adds it automatically).

### Working with teams

```php
$user->ownedTeams;          // Teams owned by the user
$user->teams;               // Teams the user belongs to
$user->currentTeam;         // The user's current team
$user->personalTeam();      // The user's personal team
$user->switchTeam($team);   // Switch the current team
$user->belongsToTeam($team);
$user->ownsTeam($team);
```

### Invitations

```php
use JeffersonGoncalves\Teams\Models\TeamInvitation;

$invitation = TeamInvitation::create([
    'team_id' => $team->id,
    'email' => 'invited@example.com',
]);

$invitation->accept($user); // Attaches the user and deletes the invitation
```

## Configuration

The published `config/teams.php` file lets you customize the authentication guard,
the user model, whether personal teams are created automatically, the Eloquent models,
and the table names.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jèfferson Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
