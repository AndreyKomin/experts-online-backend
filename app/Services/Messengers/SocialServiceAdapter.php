<?php

namespace App\Messengers\Services;

use App\Contracts\IMessengerService;
use App\Contracts\ISocialDriver;
use App\Models\User;

class SocialServiceAdapter implements IMessengerService
{
    protected $driver;

    public function __construct(string $driver)
    {
        $this->driver = $driver;
    }

    public function sendAuth(string $code): array
    {
        $driverClass = 'App\\Services\\Messengers\Socials\\' . ucfirst($this->driver) . 'Driver';

        if (!class_exists($driverClass)) {
            throw new \Exception();
        }

        /** @var ISocialDriver $driver */
        $driver = app($driverClass);
        $driver->getToken($code);
    }
}
