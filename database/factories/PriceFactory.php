<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Price::class, function (Faker $faker) {
    return [
        'price'=>mt_rand(1,4999)
    ];
});
