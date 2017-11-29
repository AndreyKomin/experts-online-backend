<?php

namespace App\Services;


use App\Models\Repositories\UserMessengerRepository;
use App\Models\User;
use App\Models\UserMessenger;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Validation\Factory;
use App\Models\Messenger;

class UserMessengersServiceManager extends ServiceManager
{
    protected $repository;

    public function __construct(
        UserMessengerRepository $repository,
        Factory $validationFactory,
        ConnectionInterface $connection
    ) {
        $this->repository = $repository;
        parent::__construct($validationFactory, $connection);
    }

    public function create(User $user, Messenger $messenger, string $unique): UserMessenger
    {
        $userMessenger = new UserMessenger();
        $userMessenger->user()->associate($user);
        $userMessenger->messenger()->associate($messenger);
        $userMessenger->messenger_unique_id = $unique;
        $this->repository->save($userMessenger);
        return $userMessenger;
    }
}