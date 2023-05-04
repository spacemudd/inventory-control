<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorRep extends Model
{
    protected $fillable = [
        'vendor_id',
        'name',
        'email',
        'number',
        'position',
    ];

    protected $dateFormat = 'Y-m-d H:i:s.u';
}
