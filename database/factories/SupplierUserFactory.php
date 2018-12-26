<?php

use Faker\Generator as Faker;

$factory->define(App\Models\SupplierUser::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'email_verified_at' => now(),
        'password' => bcrypt('123456'), // secret
        'remember_token' => str_random(10),
    ];
});
