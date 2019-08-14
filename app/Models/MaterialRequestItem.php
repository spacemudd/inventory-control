<?php

namespace App\Models;

use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;

class MaterialRequestItem extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = [
        'material_request_id',
        'item_id',
        'description',
        'qty',
    ];

    public function materialRequest()
    {
        return $this->belongsTo(MaterialRequest::class);
    }
}
