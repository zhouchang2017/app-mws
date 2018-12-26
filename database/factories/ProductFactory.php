<?php

use Faker\Generator as Faker;

$factory->define(App\Models\DP\Product::class, function (Faker $faker) {
    return [
        'code' => $faker->uuid,
        'enabled' => mt_rand(0, 1),
        'check_state' => 'saved',
    ];
});
