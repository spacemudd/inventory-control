<?php

namespace App\Models;

use Brick\Money\Money;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class QuotationItem extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = [
        'quotation_id',
        'material_request_item_id',
        'vendor_id',
        'description',
        'qty',
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
