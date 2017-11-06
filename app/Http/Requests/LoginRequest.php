<?php

namespace App\Http\Requests;

use Dingo\Api\Http\Request;

class LoginRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => 'required|string',
            'password' => 'required|string'
        ];
    }
}
