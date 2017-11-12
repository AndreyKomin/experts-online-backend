<?php

namespace App\Models\Repositories;

use App\Models\User;
use App\Models\UserMessenger;
use App\Models\Messenger;
use Illuminate\Database\Eloquent\Builder;

class UserMessengerRepository extends BaseRepository
{
    public function getQuery(): Builder
    {
        return UserMessenger::query();
    }

    public function findOrFailByUniqueAndMessenger(string $uniqueId, Messenger $messenger): ?UserMessenger
    {
        /** @var UserMessenger $userMessenger */
        $userMessenger = $this->getQuery()
            ->where(UserMessenger::UNIQUE, '=', $uniqueId)
            ->where(UserMessenger::MESSENGER_ID, '=', $messenger->id)
            ->firstOrFail();
        return $userMessenger;
    }

}