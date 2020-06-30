<?php

namespace App\Models;

use App\Scopes\LimitByRegionScope;
use Brick\Math\RoundingMode;
use Brick\Money\Context\CashContext;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderLine extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'lump_sum' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($line) {
            $line->subtotal = Money::of($line->unit_price, 'SAR')->multipliedBy($line->qty)->getAmount()->toFloat();

            if ($line->lump_sum) $line->subtotal = $line->unit_price;

           // $line->vat = Money::of($line->subtotal, 'SAR', new CashContext(2), RoundingMode::HALF_UP)->multipliedBy(0.05, RoundingMode::HALF_UP)->getAmount()->toFloat();
            $oldVat = 0.05;
            $newSaudiVat = 0.15;
            $d = now()->setDate(2020, 6, 29);
            if (now()->greaterThan($d)) {
                $line->vat = round($line->subtotal*$newSaudiVat, 2);
            } else {
                $line->vat = round($line->subtotal*$oldVat, 2);
            }

            $line->grand_total = Money::of($line->subtotal, 'SAR')->plus($line->vat)->getAmount()->toFloat();
        });

        static::updating(function($line) {
            $line->subtotal = Money::of($line->unit_price, 'SAR', new CashContext(2), RoundingMode::HALF_UP)->multipliedBy($line->qty, RoundingMode::HALF_UP)->getAmount()->toFloat();

            if ($line->lump_sum) $line->subtotal = $line->unit_price;

            //$line->vat = Money::of($line->subtotal, 'SAR')->multipliedBy(0.05, RoundingMode::HALF_UP)->getAmount()->toFloat();
            $oldVat = 0.05;
            $newSaudiVat = 0.15;
            $d = now()->setDate(2020, 6, 29);
            if (now()->greaterThan($d)) {
                $line->vat = round($line->subtotal*$newSaudiVat, 2);
            } else {
                $line->vat = round($line->subtotal*$oldVat, 2);
            }
            $line->grand_total = Money::of($line->subtotal, 'SAR')->plus($line->vat)->getAmount()->toFloat();
        });
    }
}
