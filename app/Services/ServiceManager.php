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

    protected static $validationRules = [];

    public function __construct(
        string $className,
        IRepositoryFactory $repositoryFactory,
        Factory $validationFactory,
        ConnectionInterface $connection
    ) {
        $this->validationFactory = $validationFactory;
        $this->connection = $connection;
        $this->repositoryFactory = $repositoryFactory;
        $this->className = $className;
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
        $this->validate($data, static::$validationRules);
        return $this->repositoryFactory->getRepository($this->className)->save((new $this->className($data)));
    }

    public function update(Model $model, array $data): Model
    {
        $this->validate($data, static::$validationRules);
        $model->fill($data);
        return $this->repositoryFactory->getRepository($this->className)->save($model);
    }

    public function delete(Model $model): void
    {
        $this->repositoryFactory->getRepository($this->className)->delete($model);
    }
}
