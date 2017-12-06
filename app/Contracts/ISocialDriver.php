<?php

namespace App\Contracts;

use App\Services\Messengers\Dto\SocialUser;
use App\Services\Messengers\Dto\Token;

interface ISocialDriver
{
    public function getToken(string $code): Token;
    public function getInfo(Token $token): SocialUser;
}
