<?php

namespace App\Contracts;

interface IMessengerService
{
    public function sendAuth(string $code): IMessengerUser;
}