<?php

namespace App\Services\Messengers\Dto;

class Token
{
    public $token;
    public $expiresIn;

    public function __construct(array $data)
    {
        foreach ($data as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }
}