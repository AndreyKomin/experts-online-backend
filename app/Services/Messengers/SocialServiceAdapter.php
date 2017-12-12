<?php

namespace App\Messengers\Services;

use App\Contracts\IMessengerService;
use App\Contracts\IMessengerUser;
use App\Contracts\ISocialDriver;
use App\Models\User;

class SocialServiceAdapter implements IMessengerService
{
    protected $driver;

    public function __construct(string $driver)
    {
        $this->driver = $driver;
    }

    public function sendAuth(string $code): IMessengerUser
    {
        $driverClass = 'App\\Services\\Messengers\Socials\\' . ucfirst($this->driver) . 'Driver';

        if (!class_exists($driverClass)) {
            throw new \Exception();
        }

        /** @var ISocialDriver $driver */
        $driver = app($driverClass);
        try {
            return $driver->getInfo($driver->getToken($code));
        } catch (\Exception $exception) {
            var_dump($exception->getMessage()); die;
        }

    }
}
