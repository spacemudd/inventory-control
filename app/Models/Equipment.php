<?php

namespace App\Models;

use App\Scopes\LimitByRegionScope;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Equipment extends Model implements AuditableContract
{
    use Auditable, NodeTrait;
     public $incrementing = false;
    protected $table = 'equipments';
    protected $fillable = [
        'id',
        'name',
        'type',
        'is_leaf',
    ];

    protected $appends = [
        'dragDisabled',
        'addTreeNodeDisabled',
        'addLeafNodeDisabled',
        'editNodeDisabled',
        'delNodeDisabled',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($equipment) {
            if (!$equipment->type) {
                $equipment->type = 'equipment'; // and 'category'.
            }
        });
    }
    public function getdragDisabledAttribute()
    {
        return false;
    }

    public function getaddTreeNodeDisabledAttribute()
    {
        return false;
    }

    public function getaddLeafNodeDisabledAttribute()
    {
        return false;
    }

    public function geteditNodeDisabledAttribute()
    {
        return false;
    }

    public function getdelNodeDisabledAttribute()
    {
        return false;
    }
}
