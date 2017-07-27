<?php

$factory->define(App\Models\Core\Provider::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->name,
    ];
});
