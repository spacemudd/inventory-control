<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Stock extends Model
{
    use SoftDeletes;

    protected $table = 'stock';

    protected $fillable = [
        'code',
        'description',
        'rack_number',
        'material_request_item_id',
        'category_id',
        'recommended_qty',
    ];

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $casts = [
        'rack_number' => 'integer',
    ];

    protected $appends = [
        'on_hand_quantity'
    ];

    public function movement()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getOnHandQuantityAttribute()
    {
        $stockMovements = $this->movement()->get();

        return $stockMovements->sum('in') - $stockMovements->sum('out');
    }

    public function job_order_items()
    {
        return $this->hasMany(JobOrderItem::class);
    }

    public function quotation_items()
    {
        return $this->hasMany(QuotationItem::class);
    }
}
