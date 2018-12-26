<?php

use Faker\Generator as Faker;

$factory->define(App\Models\DP\ProductAttribute::class, function (Faker $faker) {
    return [
        'code' => $faker->uuid,
        'type' => 'text',
        'storage_type' => 'text',
        'configuration' => null,
        'position' => 0,
    ];
});
