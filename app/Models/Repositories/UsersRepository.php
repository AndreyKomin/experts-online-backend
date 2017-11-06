<?php

namespace App\Models\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User;

class UsersRepository extends BaseRepository
{
    public function getQuery(): Builder
    {
        return User::query();
    }
}
