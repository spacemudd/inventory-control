<?php

namespace App\Models;
use App\Models\Vendor;

use Illuminate\Database\Eloquent\Model;

class SupplierInvoice extends Model
{
    protected $guarded = ['id'];

    protected $dates = [
    	'created_at',
    	'updated_at',
    	'date',
        'proceeded_date',
    ];
    
    public function vendors()
    {
    	return $this->hasOne(Vendor::class, 'id', 'vendor_id');
    	//return $this->belongsTo(Vendor::class, 'suppldier_invoice_id');
    }
}
