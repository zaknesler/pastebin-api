<?php

use Faker\Generator as Faker;

$factory->define(App\Paste::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(6),
        'body' => $faker->paragraph,
        'visibility' => 'public',
    ];
});

$factory->state(App\Paste::class, 'public', [
    'visibility' => 'public',
]);

$factory->state(App\Paste::class, 'private', [
    'visibility' => 'private',
]);

$factory->state(App\Paste::class, 'unlisted', [
    'visibility' => 'unlisted',
]);

$factory->state(App\Paste::class, 'expired', [
    'expires_at' => now()->subYears(3),
]);
