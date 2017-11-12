<?php

namespace App\Http\Requests;


use Dingo\Api\Http\Request;

class BotsRegisterRequest extends Request
{
    public function rules(): array
    {
        return [
            'login' => 'required|string',
            'code' => 'required|string',
            'chatId' => 'required|string',
        ];
    }

    public function getLogin(): string
    {
        return $this->post('login');
    }

    public function getCode(): string
    {
        var_dump($this->get('login'));
        die;
        return $this->post('code');
    }

    public function getChatId(): string
    {
        return $this->post('chatId');
    }
}
