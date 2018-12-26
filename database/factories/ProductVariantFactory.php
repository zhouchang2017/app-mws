<?php

use Faker\Generator as Faker;

$factory->define(App\Models\DP\ProductVariant::class, function (Faker $faker) {
    return [
        'code' => $faker->uuid,
        'width' => $faker->randomFloat(),
        'height' => $faker->randomFloat(),
        'length' => $faker->randomFloat(),
        'weight' => $faker->randomFloat(),
    ];
});
