<?php

namespace App\Models\Repositories;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Messenger;

class MessengerRepository extends BaseRepository
{
    public function getQuery(): Builder
    {
        return Messenger::query();
    }

    public function findOrFailByCode(string $code): Messenger
    {
        /** @var Messenger $messenger */
        $messenger =  $this->getQuery()->where(Messenger::CODE, '=', $code)->firstOrFail();
        return $messenger;
    }
}