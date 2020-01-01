<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Vendor;

class CostApproval extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
    	'approved_by_text' => 'json',
    	'prepared_by_text' => 'json',
    ];

    protected $dates = [
    	'created_at', 'updated_at', 'date',
    ];

    public function requested_by()
    {
    	return $this->belongsTo(Employee::class, 'requested_by_id');
    }

    public function cost_center()
    {
    	return $this->belongsTo(Department::class, 'cost_center_id');
    }

    public function lines()
    {
        return $this->hasMany(CostApprovalLine::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function getTotalPriceAttribute()
    {
        $totalPrice = 0;

        foreach ($this->lines as $line) {
            $totalPrice += $line->unit_price * $line->qty;
        }

        return $totalPrice;
    }

    public function getVatAttribute()
    {
        return $this->total_price * 0.05;
    }

    public function getGrandTotalAttribute()
    {
        return $this->total_price * 1.05;
    }
}
