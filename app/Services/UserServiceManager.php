<?php

namespace App\Services;

use App\Contracts\IMessengerServiceFactory;
use App\Models\Messenger;
use App\Models\User;
use App\Models\UserMessenger;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\IRepositoryFactory;
use App\Contracts\IServiceManager;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Validation\Factory;

class UserServiceManager extends ServiceManager
{
    protected $className = User::class;

    protected $serviceFactory;

    public function __construct(
        IRepositoryFactory $repositoryFactory,
        Factory $validationFactory,
        ConnectionInterface $connection,
        IMessengerServiceFactory $serviceFactory
    ) {
        $this->serviceFactory = $serviceFactory;
        parent::__construct($repositoryFactory, $validationFactory, $connection);
    }

    public function authenticate(array $data): User
    {
        return $this->transaction(function () use ($data) {
            $messenger = $this->repositoryFactory->getRepository(Messenger::class)->getWhere([
                Messenger::CODE => $data['provider']
            ])->first();

            $serviceData = $this->serviceFactory->getDriver($messenger->code)->sendAuth($data['code']);

            $userMessenger = $this->repositoryFactory->getRepository(UserMessenger::class)->getWhere([
                UserMessenger::UNIQUE => $serviceData['id'],
                UserMessenger::MESSENGER_ID => $messenger->id
            ])->first();
            /** @var User $user */
            if (!$userMessenger) {
                $data['messengers'][] = [
                    'messenger_unique_id' => $serviceData['id'],
                    'messenger_id' => $messenger->id
                ];
                $user = $this->create($data);
            } else {
                $user = $this->repositoryFactory->getRepository(User::class)->find($userMessenger->user_id);
            }
            return $user;
        });
    }

    public function create(array $data): Model
    {
       return $this->transaction(function () use ($data) {
           $data['login'] = $data['login'] ?? $this->generateLogin();
           $user = parent::create($data);
           foreach ($data['messengers'] as $messenger) {
                $messenger['user_id'] = $user->id;
                $this->validate($messenger, UserMessenger::rules());
                $this->repositoryFactory->getRepository(UserMessenger::class)->save(new UserMessenger($messenger));
           }
           return $user;
       });
    }

    public function update(Model $model, array $data): Model
    {
        return $this->transaction(function () use ($model, $data) {
            $user = parent::update($model, $data);
            foreach ($data['messengers'] as $messenger) {
                $messenger['user_id'] = $user->id;
                $this->validate($messenger, UserMessenger::rules());
                $this->repositoryFactory->getRepository(UserMessenger::class)->save(new UserMessenger($messenger));
            }
            return $user;
        });
    }

    public function delete(Model $model): void
    {
        $this->transaction(function () use ($model) {
            parent::delete($model);
        });
    }

    protected function getValidationRules(): array
    {
        return User::rules();
    }

    protected function generateLogin(): string
    {
        $id = $this->repositoryFactory->getRepository(User::class)->getQuery()->pluck('id')->last();
        return "id$id";
    }
}
