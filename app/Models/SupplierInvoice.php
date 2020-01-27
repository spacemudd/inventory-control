<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierInvoice extends Model
{
    protected $guarded = ['id'];

    protected $dates = [
    	'created_at',
    	'updated_at',
    	'date',
    ];
}
