<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Vendor;
use Illuminate\Support\Str;
use NumberFormatter;

class CostApproval extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
    	'approved_by_text' => 'json',
    	'prepared_by_text' => 'json',
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
        return $this->total_price * 0.05;
    }

    public function getGrandTotalAttribute()
    {
        return $this->total_price * 1.05;
    }

    public function grandTotalInWords()
    {
        $grandTotal = round($this->grand_total, 2);

        $formatter = new NumberFormatter("en", NumberFormatter::SPELLOUT);

        $moneyText = $formatter->format($grandTotal);
        $moneyText = ucwords($moneyText);

        if (Str::contains($moneyText, 'Point')) {
            $moneyText = Str::replaceFirst('Point', 'Saudi Riyals And', $moneyText);
            $moneyTextWithCurrency = $moneyText.' Halalas Only';
        } else {
            $moneyTextWithCurrency = $moneyText.' Saudi Riyals';
        }

        return implode('-', array_map('ucfirst', explode('-', $moneyTextWithCurrency)));
    }
}
