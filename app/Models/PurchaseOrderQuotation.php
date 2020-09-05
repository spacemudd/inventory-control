<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PurchaseOrderQuotation extends Model
{
	protected $fillable = [
			'quotation_number',
	];

    protected $casts = [
        'created_at'  => 'date:d-m-Y',
    ];

    protected $dateFormat = 'd-m-Y';
	
	public function purchase_order()
	{
		return $this->belongsTo(PurchaseOrder::class);
	}
}
