<?php

namespace App\Models;

use Brick\Money\Money;
use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    protected $fillable = [
        'quotation_id',
        'material_request_item_id',
        'vendor_id',
        'description',
        'qty',
        'qty_boxes',
        'unit_price',
        'vat',
        'total_price_ex_vat',
        'total_price_inc_vat',
    ];

    public function getDisplayNameAttribute()
    {
        return $this->quotation->vendor_quotation_number;
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function getUnitPriceAttribute($value)
    {
        return Money::ofMinor($value, 'SAR')
            ->getAmount()
            ->toFloat();
    }
}
