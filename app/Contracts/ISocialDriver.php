<?php

namespace App\Contracts;

interface ISocialDriver
{
    public function getToken(string $code): array;
    public function getInfo(string $token): array;
}
