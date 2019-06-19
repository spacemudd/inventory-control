<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stock';

    protected $fillable = [
        'description',
        'material_request_item_id',
        'category_id',
        'recommended_qty',
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
}
