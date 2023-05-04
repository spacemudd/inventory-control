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

    protected $dateFormat = 'Y-m-d H:i:s.u';
    
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
