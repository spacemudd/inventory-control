<?php

use Faker\Generator as Faker;

$factory->define(App\Models\MaterialRequest::class, function (Faker $faker) {
    static $cost_center_id;

    return [
        'date' => now(),
        'location_id' => factory(\App\Models\Location::class)->create()->id,
        'cost_center_id' => $cost_center_id ?: factory(\App\Models\Department::class)->create()->id,
        'created_by_id' => factory(\App\Models\User::class)->create()->id,
        'number' => rand(),
        'status' => \App\Models\MaterialRequest::APPROVED,
    ];
});
