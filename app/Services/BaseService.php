<?php
namespace App\Services;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

abstract class BaseService
{
    protected $validationFactory;

    public function __construct(Factory $validationFactory)
    {
        $this->validationFactory = $validationFactory;
    }

    public function validate(array $data, array $rules, array $messages = []): void
    {
        /** @var Validator $validator Validator instance */
        $validator = $this->validationFactory->make($data, $rules, $messages);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
