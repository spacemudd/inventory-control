<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PurchaseOrderQuotation extends Model
{
	protected $fillable = [
			'quotation_number',
	];
	
	public function purchase_order()
	{
		return $this->belongsTo(PurchaseOrder::class);
	}
}
