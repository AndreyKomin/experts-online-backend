<?php

use Faker\Generator as Faker;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(\App\Models\User::class, function (Faker $faker) {
    static $password;

    return [
        User::FIRST_NAME => $faker->firstName,
        User::LAST_NAME => $faker->lastName,
        User::LOGIN => $faker->unique()->safeEmail,
        User::PASSWORD => $password ?: $password = bcrypt('123456'),
        User::REMEMBER_TOKEN => str_random(10),
        User::RATING => 0.00
    ];
});
