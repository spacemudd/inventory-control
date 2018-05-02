<?php
/**
 * Copyright (c) 2018 - Clarastars, LLC - All Rights Reserved.
 *
 * Unauthorized copying of this file via any medium is strictly prohibited.
 * This file is a proprietary of Clarastars LLC and is confidential.
 *
 * https://clarastars.com - info@clarastars.com
 *
 */

namespace App\Models;

use Brick\Money\Money;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;

class PurchaseOrdersItem extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = [
        'purchase_order_id',
        'item_id',
        'code',
        'name',
        'description',
        'manufacturer_id',
        'manufacturer_serial_number',
        'system_tag_number',
        'finance_tag_number',
        'rfid',
        'unit_price_minor',
        'qty',
        'total_minor',
        'warranty_expires_at',
        'received_at',
        'received_by_id',
    ];

	protected $dates = ['created_at', 'updated_at', 'date', 'received_at', 'warranty_expires_at'];

    protected $hidden = ['unit_price_minor', 'total_minor'];

    protected $appends = ['unit_price', 'total'];

	public function purchase_order()
	{
		return $this->belongsTo(PurchaseOrder::class);
	}

	public function item()
	{
	    return $this->belongsTo(Item::class);
	}

    public function getUnitPriceAttribute()
    {
        return Money::ofMinor($this->unit_price_minor, 'SAR')->getAmount();
    }

    public function getTotalAttribute()
    {
        return Money::ofMinor($this->total_minor, 'SAR')->getAmount();
    }
}