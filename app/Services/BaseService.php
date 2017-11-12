<?php
namespace App\Services;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

abstract class BaseService
{
    protected $validationFactory;

    protected $connection;

    public function __construct(Factory $validationFactory, ConnectionInterface $connection)
    {
        $this->validationFactory = $validationFactory;
        $this->connection = $connection;
    }

    public function validate(array $data, array $rules, array $messages = []): void
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
}
