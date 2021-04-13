<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Todo;
use Faker\Generator as Faker;
use Faker\Provider\DateTime;

$factory->define(Todo::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'todo' => $faker->paragraph,
        'date'=>$faker->date($format = 'Y-m-d', $max = 'now'),
        'user_id' => factory(App\User::class),
    ];
});
