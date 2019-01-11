<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Address::class, function (Faker $faker) {

    $tel = mt_rand(0,1) === 1 ? '0'.mt_rand(10,999).'-'.mt_rand(10000000,99999999):mt_rand(10,999).'-'.mt_rand(10000000,99999999);
    return [
        'name'=>$faker->name,
        'tel'=>$tel,
        'phone'=>$faker->phoneNumber,
        'fax'=>$tel,
        'zip'=>$faker->postcode,
        'country'=>'CN',
        'province'=>$faker->state,
        'city'=>$faker->city,
        'district'=>$faker->address,
        'address'=>$faker->streetAddress
    ];
});
