<?php

use Faker\Generator as Faker;

$factory->define(App\Questions::class, function (Faker $faker) {
    return [
        'content' => $faker->text,
    ];
});
