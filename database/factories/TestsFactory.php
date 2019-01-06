<?php

use Faker\Generator as Faker;
use App\Committees as Com;

$factory->define(App\Tests::class, function (Faker $faker) {
    $now = date('Y-m-d H:i:s');
    $plus2 = strtotime("+2 Hours");
    $twoHoursLater = date("Y-m-d H:i:s", $plus2);

    $com = new Com();
    $com = $com::All();
    $id_com = $com[rand(0,$com->count()-1)]->id_com;

    return [
        'title' => substr($faker->text,0,40),
        'information' => $faker->text,
        'start' => $now,
        'end' => $twoHoursLater,
        'id_com' => $id_com,
    ];
});
