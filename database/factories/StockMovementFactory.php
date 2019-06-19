<?php

use App\Models\Stock;
use App\Models\StockMovement;
use Faker\Generator as Faker;

$factory->define(StockMovement::class, function (Faker $faker) {
    static $stock_id;
    static $in;
    static $out;

    return [
        'stock_id' => $stock_id ?: factory(Stock::class)->create()->id,
        'material_request_item_id' => null,
        'quotation_item_id' => null,
        'in' => $in ?: $faker->randomNumber(3),
        'out' => $out ?: $faker->randomNumber(3),
    ];
});
