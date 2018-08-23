<?php

use Faker\Generator as Faker;

$factory->define(App\Paste::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(6),
        'body' => $faker->paragraph,
        'visibility' => 'public',
    ];
});
