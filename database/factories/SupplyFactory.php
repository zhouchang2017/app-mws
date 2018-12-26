<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Supply::class, function (Faker $faker) {
    return [
        'description'=>$faker->paragraph(),
        'has_ship'=>true
    ];
});
