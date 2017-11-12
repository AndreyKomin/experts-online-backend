<?php

namespace App\Http\Requests;

use Dingo\Api\Http\Request;

class PrependAuthRequest extends Request
{
    public function rules(): array
    {
        return [
            'login' => 'required|string',
            'messenger_id' => 'required|int'
        ];
    }
}
