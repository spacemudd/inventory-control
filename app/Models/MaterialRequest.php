<?php

namespace App\Models;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\LimitByRegionScope;

class MaterialRequest extends Model implements AuditableContract
{
    use Auditable;

    /**
     * @see getStatusNameAttribute
     */
    const PENDING = 0;

    /**
     * @see getStatusNameAttribute
     */
    const DELIVERED = 1;

    /**
     * @see getStatusNameAttribute
     */
    const APPROVED = 2;

    /**
     * @var array
     */
    protected $fillable = [
        'location_id',
        'date',
        'cost_center_id',
        'created_by_id',
        'number',
        'status',
        'region_id',
        'purpose'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'date',
    ];

    /**
     * @var array
     */
    protected $appends = [
        'can_edit', 'status_name'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new LimitByRegionScope);
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function cost_center()
    {
        return $this->belongsTo(Department::class);
    }

    public function items()
    {
        return $this->hasMany(MaterialRequestItem::class);
    }

    /**
     *
     * @return string
     */
    public function getStatusNameAttribute()
    {
        switch ($this->status) {
            case self::PENDING:
                return 'Pending';
            case self::DELIVERED:
                return 'Delivered';
            case self::APPROVED:
                return 'Approved';
        }
    }

    /**
     * Status CSS Class.
     *
     * @return string
     */
    public function getStatusCssClassAttribute()
    {
        // Space before the class to accommodate for proper html.
        switch ($this->status) {
            case self::PENDING:
                return '';
            case self::DELIVERED:
                return ' is-success';
            case self::APPROVED:
                return ' is-warning';
        }
    }

    /**
     *
     * @return bool
     */
    public function getCanEditAttribute()
    {
        return true;
        return (int) $this->status === self::PENDING;
    }

    /**
     *
     * @param $q
     * @return mixed
     */
    public function scopePending($q)
    {
        $q->where('status', self::PENDING);
    }

    public function scopeDelivered($q)
    {
        $q->where('status', self::DELIVERED);
    }

    /**
     *
     * @param $q
     * @return mixed
     */
    public function scopeApproved($q)
    {
        $q->where('status', self::APPROVED);
    }

    /**
     * All "open" material requests in the sense that they can still
     * be referenced to new quotes.
     *
     * @param $q
     */
    public function scopeOpen($q)
    {
        $q->whereIn('status', [self::APPROVED, self::PENDING, self::DELIVERED]);
    }
}
