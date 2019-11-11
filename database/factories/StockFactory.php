<?php

use App\Models\Stock;
use Faker\Generator as Faker;

$factory->define(Stock::class, function (Faker $faker) {
    return [
        'description' => $faker->text(191),
        'code' => null,
        'rack_number' => rand(1, 999),
    ];
});
