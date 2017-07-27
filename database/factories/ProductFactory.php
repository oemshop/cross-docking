<?php

$factory->define(App\Models\Catalog\Product::class, function (Faker\Generator $faker) {
    static $quantity, $price;

    return [
        'code' => $faker->userName,
        'title' => $faker->name,
        'quantity' => $quantity ?: $quantity = rand(1, 10),
        'price' => $price ?: $price =rand(10, 20),
    ];
});
