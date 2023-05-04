<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'stock_id',
        'material_request_item_id',
        'quotation_item_id',
        'stockable_id',
        'stockable_type',
        'in',
        'out',
    ];

    protected $dateFormat = 'Y-m-d H:i:s.u';

    public function stockable()
    {
        return $this->morphTo();
    }
    

}
