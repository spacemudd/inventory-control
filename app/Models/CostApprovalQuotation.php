<?php

namespace App\Models;

use App\CostApproval;
use Illuminate\Database\Eloquent\Model;

class CostApprovalQuotation extends Model
{
    protected $fillable = [
        'quotation_number',
    ];

    public function cost_approval()
    {
        return $this->belongsTo(CostApproval::class);
    }
}
