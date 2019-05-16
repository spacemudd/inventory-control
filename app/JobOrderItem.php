<?php

namespace App;

use App\Models\JobOrder;
use Illuminate\Database\Eloquent\Model;

class JobOrderItem extends Model
{
    protected $fillable = [
        'job_order_id',
        'stock_id',
        'qty',
        'dispatched_at'
    ];

    protected $dates = [
        'dispatched_at'
    ];

    public function jobOrder()
    {
        return $this->belongsTo(JobOrder::class);
    }
}
