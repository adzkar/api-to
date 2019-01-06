<?php

use Faker\Generator as Faker;
use App\Verification as Ver;

$factory->define(App\Participants::class, function (Faker $faker) {
    $ver = new Ver();
    $ver = $ver::where('status','p')->get();
    $total = $ver->count();
    $id_ver = $ver[rand(0,$total-1)]->id_ver;

    return [
        'first_name' => substr($faker->firstName, 0, 20),
        'last_name' => substr($faker->lastName, 0, 20),
        'username' => $faker->userName,
        'password' => $faker->password,
        'school' => $faker->name,
        'id_ver' => $id_ver,
    ];
});
