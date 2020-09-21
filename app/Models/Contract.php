<?php

namespace App\Models;

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
        'cost_center_id',
        'vendor_id',
        'vendor_reference',
        'remarks',
        'cost',
        'total_cost',
        'payment_frequency',
        'created_by_id',
    	'automatic_renewal'
    ];

    protected $dates = [
        'issued_at',
        'expires_at',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'show_url',
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
        return $this->belongsTo(Department::class, 'cost_center_id');
    }

    public function equipments()
    {
        return $this->belongsToMany(Equipment::class, 'contract_equipments')->withPivot('location_id');
    }

    public function payments()
    {
        return $this->hasMany(ContractPayment::class);
    }

    public function isDraft()
    {
        return (int) $this->status === (int) self::STATUS_DRAFT;
    }

    public function isSaved()
    {
        return (int) $this->status === (int) self::STATUS_SAVED;
    }

    /**
     * Get all the media files associated with this record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function media()
    {
    	return $this->morphMany(Media::class, 'model');
    }

    /**
     * Get all the notes owned by this requisition.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notes()
    {
    	return $this->morphMany(Note::class, 'notable');
    }

    public function getStatusNameAttribute()
    {
        if ((int) $this->status === (int) self::STATUS_DRAFT) {
            return 'Draft';
        }

        if ((int) $this->status === (int) self::STATUS_SAVED) {
            return 'Saved';
        }
    }

    public function getShowUrlAttribute()
    {
        return route('contracts.show', $this->id);
    }
}
