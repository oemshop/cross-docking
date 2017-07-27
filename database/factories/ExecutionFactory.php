<?php

$factory->define(App\Models\Core\Execution::class, function (Faker\Generator $faker) {
    return [
        'data_rows' => rand(1, 1000),
        'finished' => false,
        'started_at' => date('Y-m-d H:i:s'),
    ];
});
