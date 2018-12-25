<?php

use Faker\Generator as Faker;

$factory->define(App\Models\InventoryAction::class, function (Faker $faker) {
    $product_id = mt_rand(1, 50);
    $variant_id = mt_rand(1, 150);
    return [
        config('inventory.product_key') => $product_id,
        config('inventory.variant_key') => $variant_id,
        'quantity'                      => mt_rand(1, 200),
    ];
});
