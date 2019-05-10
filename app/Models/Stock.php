<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stock';

    protected $fillable = [
        'description',
        'material_request_item_id',
    ];

    public function movement()
    {
        return $this->hasMany(StockMovement::class);
    }
}
