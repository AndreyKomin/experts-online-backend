<?php

namespace App\Messengers\Services;

use App\Contracts\IMessengerService;
use App\Contracts\IMessengerServiceFactory;

class Factory implements IMessengerServiceFactory
{
    protected static $socialDrivers = [
        'vk',
        'facebook'
    ];

    public function getDriver(string $driver): IMessengerService
    {
        if (in_array($driver, static::$socialDrivers)) {
            return $this->buildSocialDriver($driver);
        }
        throw new \Exception;
    }

    protected function buildSocialDriver(string $driver): IMessengerService
    {
        return new SocialServiceAdapter($driver);
    }
}
