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

    protected $table = 'equipments';

    protected $fillable = [
        'name',
        'type',
    ];

    protected $appends = [
        'addTreeNodeDisabled',
        'addLeafNodeDisabled',
        'editNodeDisabled',
        'delNodeDisabled',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($equipment) {
            $equipment->type = 'equipment'; // and 'category'.
        });
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
