<?php

namespace App;

use App\Models\Employee;
use App\Models\JobOrder;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Model;

class JobOrderItem extends Model
{
    protected $fillable = [
        'job_order_id',
        'stock_id',
        'technician_id',
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

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function technician()
    {
        return $this->belongsTo(Employee::class, 'technician_id');
    }

    public function isDispatched()
    {
        return ! is_null($this->dispatched_at);
    }
}
