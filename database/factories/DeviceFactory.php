<?php

use Illuminate\Support\Facades\Hash;
use Faker\Generator as Faker;


$factory->define(App\Device::class, function (Faker $faker) {
    return [
        'primary_phone_number' => $faker->phoneNumber,
        'password' => 'password',
    ];
});
