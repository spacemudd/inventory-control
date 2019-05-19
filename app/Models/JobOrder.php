<?php

namespace App\Models;

use App\JobOrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobOrder extends Model
{
    use SoftDeletes;

    const DRAFT = 'draft';
    const APPROVED = 'approved';
    const PENDING = 'pending';
    const COMPLETED = 'completed';

    protected $fillable = [
        'job_order_number',
        'employee_id',
        'cost_center_id',
        'ext',
        'requested_through_type',
        'job_description',
        'status',
        'remark',
        'date',
        'location_id',
        'time_start',
        // 'materials_used',
        'time_end'
    ];

    protected $dates = [
        'date',
        'time_start',
        'time_end'
    ];

    protected $with = [
        'employee',
        'location',
        'department'
    ];

    /** Mutators */
    public function setTimeStartAttribute($value)
    {
        return $this->attributes['time_start'] = date('Y-m-d H:i:s', strtotime($this->date. ' '. $value));
    }

    public function setTimeEndAttribute($value)
    {
        return $this->attributes['time_end'] = date('Y-m-d H:i:s', strtotime($this->date. ' '. $value));
    }


    /** Accessors */
    public function getStatusAttribute()
    {
        return ucfirst($this->attributes['status']);
    }


    /** Relations */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'cost_center_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function technicians()
    {
        return $this->belongsToMany(Employee::class, 'job_order_technician', 'job_order_id', 'technician_id');
    }

    public function items()
    {
        return $this->hasMany(JobOrderItem::class);
    }


    /** Model properties overrides */
    public function getRouteKeyName()
    {
        return 'job_order_number';
    }

    /**
     * Determine if order is approved
     *
     * @return boolean
     */
    public function isApproved()
    {
        return $this->attributes['status'] == self::APPROVED;
    }
}