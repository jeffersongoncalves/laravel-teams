<?php

namespace JeffersonGoncalves\Teams\Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Authenticatable;
use JeffersonGoncalves\Teams\Concerns\HasTeams;

class User extends Authenticatable
{
    use HasTeams;

    protected $table = 'users';

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
