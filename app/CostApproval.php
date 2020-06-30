<?php

namespace App;

use App\Models\CostApprovalQuotation;
use App\Models\Department;
use App\Models\Employee;
use App\Models\PurchaseOrder;
use App\Models\Quotation;
use App\Models\Vendor;
use Brick\Math\RoundingMode;
use Brick\Money\Context\CustomContext;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use TNkemdilim\MoneyToWords\Converter;
use NumberFormatter;

class CostApproval extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'approved_by_text' => 'json',
        'prepared_by_text' => 'json',
        'lump_sum' => 'boolean',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'date',
    ];

    public function requested_by()
    {
        return $this->belongsTo(Employee::class, 'requested_by_id');
    }

    public function cost_center()
    {
        return $this->belongsTo(Department::class, 'cost_center_id');
    }

    public function lines()
    {
        return $this->hasMany(CostApprovalLine::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function approver_one()
    {
        return $this->belongsTo(Employee::class, 'approver_one_id');
    }

    public function approver_two()
    {
        return $this->belongsTo(Employee::class, 'approver_two_id');
    }

    public function approver_three()
    {
        return $this->belongsTo(Employee::class, 'approver_three_id');
    }

    public function purchase_orders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function quotations()
    {
        return $this->belongsToMany(Quotation::class, 'quotation_cost_approval');
    }

    public function adhoc_quotations()
    {
        return $this->hasMany(CostApprovalQuotation::class);
    }

    public function getTotalPriceAttribute()
    {
        $totalPrice = 0;

        foreach ($this->lines as $line) {
            $totalPrice += $line->unit_price * $line->qty;
        }

        return $totalPrice;
    }

    public function getVatAttribute()
    {
        $oldVat = 0.05;
        $newSaudiVat = 0.15;

        $d = now()->setDate(2020, 6, 30)->startOfDay();

        if (now()->greaterThan($d)) {
            return $this->total_price * $newSaudiVat;
        } else {
            return $this->total_price * $oldVat;
        }
    }

    public function getGrandTotalAttribute()
    {
        return Money::of($this->total_price, 'SAR', new CustomContext(2), RoundingMode::HALF_UP)
            ->plus($this->vat, RoundingMode::HALF_UP)
            ->getAmount()
            ->toFloat();
    }

    public function grandTotalInWords()
    {
        $converter = new Converter("Saudi Riyals", "Halalas");
        $grandTotal = Money::of($this->grand_total, 'SAR', new CustomContext(2), RoundingMode::HALF_UP)->getAmount();
        return ucwords($converter->convert($grandTotal));
    }

}
