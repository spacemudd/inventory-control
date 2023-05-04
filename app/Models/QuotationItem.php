<?php

namespace App\Models;

use Brick\Money\Money;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\Models\StockMovement;


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

    protected $dateFormat = 'Y-m-d H:i:s.u';

    public function getDisplayNameAttribute()
    {
        return $this->quotation->vendor_quotation_number;
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function stock_template()
    {
        return $this->hasOne(Stock::class, 'description', 'description');
    }

    public function getUnitPriceAttribute($value)
    {
        return Money::ofMinor($value, 'SAR')
            ->getAmount()
            ->toFloat();
    }
    
    
    
    public function stock_movements()
    {
    	return $this->morphMany(StockMovement::class, 'stockable');
    }
}
