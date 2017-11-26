<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

class AuthenticateRequest extends Request
{
    public function rules(): array
    {
        return [
            'login' => 'required|string',
            'messenger_id' => 'required|int'
        ];
    }
}
