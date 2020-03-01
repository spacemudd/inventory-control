<?php

namespace App;

use App\Models\CostApprovalQuotation;
use App\Models\PurchaseOrder;
use App\Models\Quotation;
use Illuminate\Database\Eloquent\Model;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Vendor;
use Illuminate\Support\Str;
use NumberFormatter;
use TNkemdilim\MoneyToWords\Converter;
use TNkemdilim\MoneyToWords\Languages;

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
        return $this->total_price * 0.05;
    }

    public function getGrandTotalAttribute()
    {
        return $this->total_price * 1.05;
    }

    public function grandTotalInWords()
    {
        $grandTotal = round($this->grand_total, 2);
        return $this->numtowords($grandTotal);

        $convert = new Converter('Saudi Riyals', 'Halalas', Languages::ENGLISH);
        return $convert->convert($grandTotal);

        //return new

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

    public function numtowords($num)
    {
        $decones = array(
            '01' => "One",
            '02' => "Two",
            '03' => "Three",
            '04' => "Four",
            '05' => "Five",
            '06' => "Six",
            '07' => "Seven",
            '08' => "Eight",
            '09' => "Nine",
            10 => "Ten",
            11 => "Eleven",
            12 => "Twelve",
            13 => "Thirteen",
            14 => "Fourteen",
            15 => "Fifteen",
            16 => "Sixteen",
            17 => "Seventeen",
            18 => "Eighteen",
            19 => "Nineteen"
        );
        $ones = array(
            0 => " ",
            1 => "One",
            2 => "Two",
            3 => "Three",
            4 => "Four",
            5 => "Five",
            6 => "Six",
            7 => "Seven",
            8 => "Eight",
            9 => "Nine",
            10 => "Ten",
            11 => "Eleven",
            12 => "Twelve",
            13 => "Thirteen",
            14 => "Fourteen",
            15 => "Fifteen",
            16 => "Sixteen",
            17 => "Seventeen",
            18 => "Eighteen",
            19 => "Nineteen"
        );
        $tens = array(
            0 => "",
            2 => "Twenty",
            3 => "Thirty",
            4 => "Forty",
            5 => "Fifty",
            6 => "Sixty",
            7 => "Seventy",
            8 => "Eighty",
            9 => "Ninety"
        );
        $hundreds = array(
            "Hundred",
            "Thousand",
            "Million",
            "Billion",
            "Trillion",
            "Quadrillion"
        ); //limit t quadrillion
        $num = number_format($num,2,".",",");
        $num_arr = explode(".",$num);
        $wholenum = $num_arr[0];
        $decnum = $num_arr[1];
        $whole_arr = array_reverse(explode(",",$wholenum));
        krsort($whole_arr);
        $rettxt = "";
        foreach($whole_arr as $key => $i){
            if($i < 20){
                $rettxt .= $ones[$i];
            }
            elseif($i < 100){
                $rettxt .= $tens[substr($i,0,1)];
                $rettxt .= " ".$ones[substr($i,1,1)];
            }
            else{
                $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0];
                $rettxt .= " ".$tens[substr($i,1,1)];
                $rettxt .= " ".$ones[substr($i,2,1)];
            }
            if($key > 0){
                $rettxt .= " ".$hundreds[$key]." ";
            }

        }
        $rettxt = $rettxt." Saudi Riyals";

        if($decnum > 0){
            $rettxt .= " and ";
            if($decnum < 20){
                $rettxt .= $decones[$decnum];
            }
            elseif($decnum < 100){
                $rettxt .= $tens[substr($decnum,0,1)];
                $rettxt .= " ".$ones[substr($decnum,1,1)];
            }
            $rettxt = $rettxt." Halalas Only";
        }
        return $rettxt;
    }
}
