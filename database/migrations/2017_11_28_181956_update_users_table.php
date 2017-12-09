<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->mediumText('avatar')->nullable();
            $table->text('portfolio')->nullable();
            $table->integer('price')->nullable();
            $table->tinyInteger('isExpert')->default(0);
            $table->tinyInteger('directInvite')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar');
            $table->dropColumn('portfolio');
            $table->dropColumn('price');
            $table->dropColumn('isExpert');
            $table->dropColumn('directInvite');
        });
    }
}
