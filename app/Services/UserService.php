<?php

namespace App\Services;

use App\Contracts\IRepository;
use App\Models\Repositories\UsersRepository;
use App\Models\User;
use Illuminate\Validation\Factory;

class UserService extends BaseService
{
    protected $repository;

    protected static $updateRules = [
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'login' => 'required|string',
        'password' => 'required|string',
    ];


    public function __construct(UsersRepository $repository, Factory $validationFactory)
    {
        $this->repository = $repository;
        parent::__construct($validationFactory);
    }

    public function save(User $user, array $fields): void
    {
        $this->validate($fields, static::$updateRules);
        $user->fill($fields);
        $this->repository->save($user);

    }

    public function delete(User $user): void
    {

    }
}
