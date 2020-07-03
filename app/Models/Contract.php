<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'number',
        'status',
        'issued_at',
        'expires_at',
        'department_id',
        'vendor_id',
        'vendor_reference',
        'remarks',
        'created_by_id',
    ];

    protected $dates = [
        'issued_at',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function created_by()
    {
        return $this->belongsTo(User::class);
    }

    public function cost_center()
    {
        return $this->belongsTo(Department::class, 'id', 'department_id');
    }
}
