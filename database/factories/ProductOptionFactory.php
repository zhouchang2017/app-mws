<?php

use Faker\Generator as Faker;

$factory->define(App\Models\DP\ProductOption::class, function (Faker $faker) {
    return [
        'code' => $faker->uuid,
        'position' => 0,
    ];
});
