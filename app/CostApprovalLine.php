<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CostApprovalLine extends Model
{
    protected $guarded = ['id'];

    public function getTotalPriceAttribute()
    {
    	return number_format($this->unit_price * $this->qty, 2);
    }
}
