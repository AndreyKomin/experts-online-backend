<?php

namespace App\Services;

use App\Contracts\IMessengerServiceFactory;
use App\Models\Messenger;
use App\Models\User;
use App\Models\UserMessenger;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\IRepositoryFactory;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;
use Illuminate\Validation\Factory;

class UserServiceManager extends ServiceManager
{
    protected $className = User::class;

    protected $serviceFactory;

    protected $creationRules = [
        User::LOGIN => 'required|string|unique:users',
        User::FIRST_NAME => 'nullable|string|max:255',
        User::LAST_NAME => 'nullable|string|max:255',
    ];

    protected $updateRules = [
        User::LOGIN => 'required|string|unique:users',
        User::FIRST_NAME => 'nullable|string|max:255',
        User::LAST_NAME => 'nullable|string|max:255',
    ];

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

            $socialUser = $this->serviceFactory->getDriver($messenger->code)->sendAuth($data['code']);

            $userMessenger = $this->repositoryFactory->getRepository(UserMessenger::class)->getWhere([
                UserMessenger::UNIQUE => $socialUser->getProperty('id'),
                UserMessenger::MESSENGER_ID => $messenger->id
            ])->first();

            /** @var User $user */
            if (!$userMessenger) {
                $data['messengers'][] = [
                    'messenger_unique_id' => $socialUser->getProperty('id'),
                    'messenger_id' => $messenger->id
                ];
                $names = explode(' ', $socialUser->getProperty('name')) ?? null;
                if ($names) {
                    $data[User::FIRST_NAME] = $names[0];
                    $data[User::LAST_NAME] = $names[1] ?? null;
                }
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
            $this->updateRules[User::LOGIN] .= ',login,' . $model->id;
            /** @var User $user */
            $user = parent::update($model, $data);
            $collection = new Collection();
            if (isset($data['messengers']) && is_array($data['messengers'])) {
                $user->messengers()->delete();
                foreach ($data['messengers'] as $messenger) {
                    $userMessenger = $this->repositoryFactory->getRepository(UserMessenger::class)
                        ->getWhere([
                            'user_id' => $user->id,
                            'messenger_id' => $messenger['messenger_id'],
                        ])->first() ?? new UserMessenger();
                    $messenger['user_id'] = $user->id;
                    $this->validate($messenger, UserMessenger::rules());
                    $userMessenger->fill($messenger);
                    $collection->push($userMessenger);
                }
            }
            $user->messengers()->saveMany($collection);
            return $user;
        });
    }

    public function delete(Model $model): void
    {
        $this->transaction(function () use ($model) {
            parent::delete($model);
        });
    }


    protected function generateLogin(): string
    {
        $id = $this->repositoryFactory->getRepository(User::class)->getQuery()
            ->orderBy('id')
            ->pluck('id')
            ->last();
        $id = (int)$id + 1;
        return "id$id";
    }
}
