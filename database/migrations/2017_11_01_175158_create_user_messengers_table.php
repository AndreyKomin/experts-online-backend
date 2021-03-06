<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserMessengersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_messengers', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('messenger_id');
            $table->string('messenger_unique_id');
            $table->string('profile_link')->nullable()->default(null);
            $table->timestamps();
            $table->unique(['user_id', 'messenger_id']);
            $table->tinyInteger('isDefault')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_messengers');
    }
}
