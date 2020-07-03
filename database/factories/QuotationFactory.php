<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Quotation::class, function (Faker $faker) {
    static $vendor_id;
    static $material_request_id;
    static $cost_center_id;

    return [
        'material_request_id' => $material_request_id ?: factory(\App\Models\MaterialRequest::class)->create()->id,
        'vendor_id' => $vendor_id,
        'cost_center_id' => $cost_center_id,
        'vendor_quotation_number' => rand(),
        'status' => \App\Models\Quotation::SAVED,
        'saved_at' => now(),
        'saved_by_id' => factory(\App\Models\User::class)->create()->id,
    ];
});
