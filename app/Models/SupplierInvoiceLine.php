<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierInvoiceLine extends Model
{
    protected $fillable = [
    	'supplier_invoice_id',
    	'description',
    	'serial_number',
    	'tag_number',
    	'remarks',
    ];

	protected $dateFormat = 'Y-m-d H:i:s.u';
}
