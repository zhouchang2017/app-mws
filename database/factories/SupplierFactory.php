<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Supplier::class, function (Faker $faker) {
    return [
        'name'=>$faker->company,
        'code'=>$faker->uuid,
        'level'=>mt_rand(1,3),
        'description'=>$faker->catchPhrase
    ];
});
