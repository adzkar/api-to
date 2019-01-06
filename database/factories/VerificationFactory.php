<?php

use Faker\Generator as Faker;

$factory->define(App\Verification::class, function (Faker $faker) {
    $now = date('Y-m-d H:i:s');
    $plus7 = strtotime("+7 day");
    $weeklater = date("Y-m-d H:i:s", $plus7);

    return [
        'start' => $now,
        'end' => $weeklater,
        'code' => substr(str_shuffle('$2y$10$TKh8H1PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm'),0,6),
        'status' => substr(str_shuffle('cpt'),0,1),
    ];
});

$factory->state(App\Verification::class, 'participants', [
    'status' => 'p',
]);

$factory->state(App\Verification::class, 'committees', [
    'status' => 'c',
]);

$factory->state(App\Verification::class, 'tests', [
    'status' => 't',
]);
