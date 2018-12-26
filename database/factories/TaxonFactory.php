<?php

use Faker\Generator as Faker;

$factory->define(App\Models\DP\Taxon::class, function (Faker $faker) {
    return [
        'code'=>$faker->uuid,
        'position'=>0,
        'image'=>$faker->imageUrl()
    ];
});
