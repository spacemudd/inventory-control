<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobOrderTechnician extends Model
{
    public $table = 'job_order_technician';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'time_start',
        'time_end',
    ];

    protected $dateFormat = 'Y-m-d H:i:s.u';
}
