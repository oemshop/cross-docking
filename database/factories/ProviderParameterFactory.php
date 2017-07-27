<?php

$factory->define(App\Models\Core\ProviderParameter::class, function (Faker\Generator $faker) {
    return [
        'param' => $faker->userName,
    ];
});
