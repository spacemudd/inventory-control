<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'name',
    ];

    protected $table = "locations";

    protected $dateFormat = 'Y-m-d H:i:s.u';
}
