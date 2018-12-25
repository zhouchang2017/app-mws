<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Warehouse::class, function (Faker $faker) {
    return [
        'name'=>$faker->city.'仓库'
    ];
});
