<?php

namespace App\Http\Requests;

use Dingo\Api\Http\Request;

class BotsDecisionRequest extends Request
{
    public function rules(): array
    {
        return [
            'code' => 'required|string',
            'decision' => 'required|bool',
            'chatId' => 'required|string',
        ];
    }

    public function getDecision(): bool
    {
        return $this->get('decision');
    }

    public function getCode(): string
    {
        return $this->get('code');
    }

    public function getChatId(): string
    {
        return $this->get('chatId');
    }
}
