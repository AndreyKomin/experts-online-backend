<?php

namespace App\Services;

use App\Models\Repositories\UsersRepository;
use App\Models\User;
use Illuminate\Database\ConnectionInterface;
use App\Models\Messenger;
use Illuminate\Validation\Factory;

class UserServiceManager extends ServiceManager
{
    protected $repository;

    protected $userMessengersService;

    protected static $validationRules = [
        User::LOGIN => 'required|string',
        User::FIRST_NAME => 'string|max:255',
        User::LAST_NAME => 'string|max:255',
    ];


    public function __construct(
        UsersRepository $repository,
        Factory $validationFactory,
        ConnectionInterface $connection,
        UserMessengersServiceManager $userMessengersService
    ) {
        $this->repository = $repository;
        $this->userMessengersService = $userMessengersService;
        parent::__construct($validationFactory, $connection);
    }

    public function create(array $fields): User
    {
        $this->validate($fields, static::$registerRules);
        /** @var User $user */
        $user = $this->repository->save((new User($fields)));
        return $user;
    }

    public function save(User $user, array $fields): void
    {
        $this->validate($fields, static::$updateRules);
        $user->fill($fields);
        $this->repository->save($user);
    }

    public function delete(User $user): void
    {
        $this->repository->delete($user);
    }

    public function createUserWithMessenger(array $userFields, Messenger $messenger, string $unique): User
    {
        return $this->transaction(function () use ($userFields, $messenger, $unique) {
            $user = $this->create($userFields);
            $this->userMessengersService->create($user, $messenger, $unique);
            return $user;
        });
    }
}
