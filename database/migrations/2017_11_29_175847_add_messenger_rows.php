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
            'code' => 'vk'
        ]);
        Messenger::query()->create([
            'name' => 'Facebook',
            'code' => 'facebook'
        ]);
        Messenger::query()->create([
            'name' => 'Goggle+',
            'code' => 'google'
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
