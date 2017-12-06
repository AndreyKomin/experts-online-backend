<?php

namespace App\Services\Messengers\Dto;

use App\Contracts\IMessengerUser;

class SocialUser implements IMessengerUser
{
    public $id;
    public $name;
    public $email;
    public $login;

    public function __construct(array $data)
    {
        foreach ($data as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    public function getProperty(string $property)
    {
        return $this->$property ?? null;
    }
}