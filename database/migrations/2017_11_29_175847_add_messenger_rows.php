<?php

use App\Models\Messenger;
use Illuminate\Database\Migrations\Migration;

class AddMessengerRows extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Messenger::query()->create([
            'name' => 'Vk',
            'code' => 'vk',
            'canUseForMessage' => 0,
            'canUseForAuth' => 1,
        ]);
        Messenger::query()->create([
            'name' => 'Facebook',
            'code' => 'facebook',
            'canUseForMessage' => 0,
            'canUseForAuth' => 1,
        ]);
        Messenger::query()->create([
            'name' => 'Goggle+',
            'code' => 'google',
            'canUseForMessage' => 0,
            'canUseForAuth' => 1,
        ]);
        Messenger::query()->create([
            'name' => 'Telegram',
            'code' => 'telegram',
            'canUseForMessage' => 1,
            'canUseForAuth' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Messenger::query()->delete();
    }
}
