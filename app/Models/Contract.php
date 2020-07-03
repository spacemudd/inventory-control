<?php

namespace App\Models;

use App\Scopes\LimitByRegionScope;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    const STATUS_DRAFT = 0;
    const STATUS_SAVED = 1;

    protected $fillable = [
        'number',
        'status',
        'issued_at',
        'expires_at',
        'department_id',
        'vendor_id',
        'vendor_reference',
        'remarks',
        'cost',
        'payment_frequency',
        'created_by_id',
    ];

    protected $dates = [
        'issued_at',
        'expires_at',
        'created_at',
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->status = self::STATUS_DRAFT;
            if (auth()->user()) {
                $model->created_by_id = auth()->user()->id;
            }
        });
    }

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
        return $this->belongsTo(Department::class);
    }
}
