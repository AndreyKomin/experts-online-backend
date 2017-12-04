<?php

namespace App\Services;

use App\Contracts\IRepositoryFactory;
use App\Contracts\IServiceManager;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class ServiceManager implements IServiceManager
{
    protected $repositoryFactory;

    protected $validationFactory;

    protected $connection;

    protected $className;

    protected $updateRules = [];

    protected $creationRules = [];

    public function __construct(
        IRepositoryFactory $repositoryFactory,
        Factory $validationFactory,
        ConnectionInterface $connection
    ) {
        $this->validationFactory = $validationFactory;
        $this->connection = $connection;
        $this->repositoryFactory = $repositoryFactory;
    }

    protected function validate(array $data, array $rules, array $messages = []): void
    {
        /** @var Validator $validator Validator instance */
        $validator = $this->validationFactory->make($data, $rules, $messages);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function transaction(\Closure $closure)
    {
        try {
            return $this->connection->transaction($closure);
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }

    public function create(array $data): Model
    {
        $this->validate($data, $this->creationRules);
        return $this->repositoryFactory->getRepository($this->className)->save((new $this->className($data)));
    }

    public function update(Model $model, array $data): Model
    {
        $this->validate($data, $this->updateRules);
        $model->fill($data);
        return $this->repositoryFactory->getRepository($this->className)->save($model);
    }

    public function delete(Model $model): void
    {
        $this->repositoryFactory->getRepository($this->className)->delete($model);
    }

    protected function getValidationRules(): array
    {
        return [];
    }
}
