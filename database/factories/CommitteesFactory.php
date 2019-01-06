<?php

use Faker\Generator as Faker;
use App\Verification as Ver;

$factory->define(App\Committees::class, function (Faker $faker) {
    $ver = new Ver();
    $ver = $ver::where('status','c')->get();
    $total = $ver->count();
    $id_ver = $ver[rand(0,$total-1)]->id_ver;

    return [
        'name' => substr($faker->name, 0, 20),
        'username' => $faker->userName,
        'password' => str_random(10),
        'id_ver' => $id_ver,
    ];
});
