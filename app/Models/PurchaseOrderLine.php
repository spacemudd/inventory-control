<?php

namespace App\Models;

use App\Scopes\LimitByRegionScope;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderLine extends Model
{
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($line) {
            if ($line->lump_sum) $line->subtotal = $line->unit_price;

            $line->vat = Money::of($line->subtotal, 'SAR')->multipliedBy(0.05)->getAmount()->toFloat();
            $line->grand_total = Money::of($line->subtotal, 'SAR')->plus($line->vat)->getAmount()->toFloat();
        });

        static::updating(function($line) {
            if ($line->lump_sum) $line->subtotal = $line->unit_price;

            $line->vat = Money::of($line->subtotal, 'SAR')->multipliedBy(0.05)->getAmount()->toFloat();
            $line->grand_total = Money::of($line->subtotal, 'SAR')->plus($line->vat)->getAmount()->toFloat();
        });
    }
}
