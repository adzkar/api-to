<?php

use Faker\Generator as Faker;

$factory->define(App\Answers::class, function (Faker $faker) {
    return [
        'option' => substr($faker->text, 10),
        'status' => false,
    ];
});

$factory->state(App\Answers::class, 't', [
    'status' => true,
]);
