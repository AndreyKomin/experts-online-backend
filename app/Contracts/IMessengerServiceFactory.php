<?php

namespace App\Contracts;


interface IMessengerServiceFactory
{
    public function getDriver(string $driver): IMessengerService;
}