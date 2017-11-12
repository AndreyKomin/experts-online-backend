<?php

namespace App\Jobs;

use App\Services\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TelegramAuth implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $uniquieId;

    public function __construct(string $uniqueId)
    {
        $this->uniquieId = $uniqueId;
    }

    public function handle(TelegramService $telegramService)
    {
        $telegramService->sendAuth($this->uniquieId);
    }
}
