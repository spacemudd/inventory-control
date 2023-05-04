<?php

namespace App\Models;

use Brick\Money\Money;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\LimitByRegionScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use SoftDeletes;

    /**
     * When the quotation is still being updated.
     *
     * @var int
     */
    const DRAFT = 0;

    /**
     * When the quotation is saved and not editable anymore.
     *
     * @var int
     */
    const SAVED = 1;

    protected $fillable = [
        'material_request_id',
        'vendor_id',
        'vendor_quotation_number',
        'status',
        'cost_center_id',
        'region_id'
    ];

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $casts = [
        'vendor_quotation_number' => 'string',
    ];

    protected $dates = [
        'created_at', 'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new LimitByRegionScope);

        static::creating(function($jobOrder) {
            if (auth()->user()) {
                $jobOrder->region_id = auth()->user()->region_id;
            }
        });
    }

    public function material_request()
    {
        return $this->belongsTo(MaterialRequest::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function cost_center()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     *
     * @return string
     */
    public function getStatusNameAttribute()
    {
        switch($this->status) {
            case self::DRAFT:
                return 'Draft';
            case self::SAVED:
                return 'Saved';
        }
    }

    /**
     *
     * @return \Brick\Math\BigDecimal
     * @throws \Brick\Money\Exception\UnknownCurrencyException
     */
    public function totalCost()
    {
        $totalPrice = $this->items()->sum('total_price_inc_vat');

        return Money::ofMinor($totalPrice, 'SAR')->getAmount();
    }

    public function scopeDraft($q)
    {
        $q->where('quotations.status', self::DRAFT);
    }

    public function scopeSavedQuotations($q)
    {
        $q->where('quotations.status', self::SAVED);
    }
    
    public static function sortByVendor($sortType='asc')
    {
    	return	static::join('vendors', 'quotations.vendor_id', '=', 'vendors.id')->select('quotations.*')->orderBy('vendors.name', $sortType);
    }
    
    public static function sortByMaterialRequest($sortType='asc')
    {
    	
    	
    	return	static::join('material_requests', 'quotations.material_request_id', '=', 'material_requests.id')->select('quotations.*')->orderBy('material_requests.number', $sortType);
    }
    
}
