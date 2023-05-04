<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Region extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = ['name'];
    protected $dateFormat = 'Y-m-d H:i:s.u';
}
