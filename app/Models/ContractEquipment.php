<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractEquipment extends Model
{
    protected $fillable = [
        'contract_id',
        'equipment_id',
        'location_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            if (auth()->user()) {
                $model->created_by_id = auth()->user()->id;
            }
        });
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
