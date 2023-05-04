<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\LimitByRegionScope;
use App\Models\StockMovement;


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
        'time_end',
        'region_id',
        'equipment_id',
        'equipment_general',
    ];

    protected $dates = [
        'date',
        'time_start',
        'time_end',
    ];

    protected $with = [
        'employee',
        'location',
        'department'
    ];

    protected $appends = [
        'dispatched_count',
        'un_dispatched_count',
        'is_completed',
        'human_date',
    ];

    //protected $dateFormat = 'Y-m-d H:i:s.u';

    protected static function boot()
    {
        parent::boot();

        if (!app()->runningInConsole()) {
            static::addGlobalScope(new LimitByRegionScope);
        }

        static::creating(function($jobOrder) {
            $jobOrder->region_id = auth()->user()->region_id;
        });
    }

    /** Mutators */
    public function setTimeStartAttribute($value)
    {
        if ($value) {
            $this->attributes['time_start'] = Carbon::parse($value);
            //return $this->attributes['time_start'] = date('Y-m-d H:i:s', strtotime($this->date. ' '. $value));
        }
    }

    public function setTimeEndAttribute($value)
    {
        if ($value) {
            $this->attributes['time_end'] = Carbon::parse($value);
            //return $this->attributes['time_end'] = date('Y-m-d H:i:s', strtotime($this->date. ' '. $value));
        }
    }


    /** Accessors */
    public function getStatusAttribute()
    {
        return ucfirst($this->attributes['status']);
    }

    public function getHumanDateAttribute()
    {
        return optional($this->date)->format('Y-m-d');
    }

    /** Accessors */
    public function getDispatchedCountAttribute()
    {
        return $this->items()->whereNotNull('dispatched_at')->count();
    }

    /** Accessors */
    public function getUnDispatchedCountAttribute()
    {
        return $this->items()->whereNull('dispatched_at')->count();
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
        //return $this->belongsTo(Location::class);
        return $this->hasOne(Location::class, 'id', 'location_id'); //belongsTo(Location::class);
    }
    
    
    public function locationdesc()
    {
    	//return $this->belongsTo(Location::class);
    	return $this->hasOne(Location::class, 'id', 'location_id')
    	->orderBy('name', 'desc')
    	; //belongsTo(Location::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function technicians()
    {
        return $this->belongsToMany(Employee::class, 'job_order_technician', 'job_order_id', 'technician_id')
            ->withPivot('time_start', 'time_end');
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
        return in_array($this->attributes['status'], [
            self::APPROVED,
            self::COMPLETED,
            self::PENDING
        ]);
    }

    /**
     *
     * @return bool
     */
    public function isPending()
    {
        return in_array($this->attributes['status'], [
            self::PENDING,
            self::DRAFT,
        ]);
    }

    public function isCompleted()
    {
        return in_array($this->attributes['status'], [
            self::COMPLETED,
        ]);
    }

    public function getIsCompletedAttribute()
    {
        return $this->isCompleted();
    }

    public function getDisplayNameAttribute()
    {
        return $this->job_order_number;
    }

    public function scopePending($q)
    {
        $q->whereIn('status', [
            self::PENDING,
            self::DRAFT,
            self::APPROVED,
        ]);
    }

    public function scopeCompleted($q)
    {
        return $q->where('status', self::COMPLETED);
    }
    
    public function stock_movements()
    {
    	return $this->morphMany(StockMovement::class, 'stockable');
    }
    
}
