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

    public function getQtyAttribute($qty)
    {
        return number_format($qty);
    }

    /**
     * Gets the last stock template for the item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function last_template()
    {
        return $this->hasOne(Stock::class, 'description', 'description');
    }

    /**
     * Gets the last stock template for the item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function stock_template()
    {
        return $this->hasOne(Stock::class, 'description', 'description');
    }

    /**
     * Gets the last quoted item for the stock.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|\Illuminate\Database\Query\Builder
     */
    public function last_quoted()
    {
        return $this->hasOne(QuotationItem::class, 'description', 'description')->latest();
    }
}
