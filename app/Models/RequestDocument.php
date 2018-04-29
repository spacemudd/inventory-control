<?php
/**
 * Copyright (c) 2018 - Clarastars, LLC - All Rights Reserved.
 *
 * Unauthorized copying of this file via any medium is strictly prohibited.
 * This file is a proprietary of Clarastars LLC and is confidential.
 *
 * https://clarastars.com - info@clarastars.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class RequestDocument extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    const UNSET = -1;
    const DRAFT = 0;
    const SAVED = 1;
    const VOID = 2;

    protected $guarded = ['id'];

    protected $appends = ['status_name', 'link'];

    public function getStatusNameAttribute()
    {
        switch($this->status) {
            case self::UNSET:
                return 'unset';
                break;
            case self::DRAFT:
                return 'draft';
                break;
            case self::SAVED:
                return 'saved';
                break;
            case self::DRAFT:
                return 'draft';
                break;

            default:
                return '';
                break;
        }
    }

    public function getLinkAttribute()
    {
        return route('requests.show', ['id' => $this->id]);
    }

    public function requested_by()
    {
        return $this->belongsTo(Employee::class);
    }

    public function cost_center_by()
    {
        return $this->belongsTo(Department::class);
    }

    public function requested_for()
    {
        return $this->belongsTo(Employee::class);
    }

    public function cost_center_for()
    {
        return $this->belongsTo(Department::class);
    }

    public function created_by()
    {
        return $this->belongsTo(User::class);
    }

    public function request_document_items()
    {
        return $this->hasMany(RequestDocumentItem::class);
    }

    public function approved_by()
    {
        return $this->belongsTo(User::class);
    }

    public function getIsDraftAttribute()
    {
        return $this->status === self::DRAFT;
    }

    public function getCanAddItemsAttribute()
    {
        return $this->status === self::DRAFT || $this->status === self::UNSET;
    }

    public function scopeDraft($q)
    {
        return $q->where('status', self::DRAFT);
    }

    public function scopeSaved($q)
    {
        return $q->where('status', self::SAVED);
    }

    public function scopeVoid($q)
    {
        return $q->where('status', self::VOID);
    }
}
