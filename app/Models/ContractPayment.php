<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractPayment extends Model
{
    protected $fillable = [
        'contract_id',
        'reference',
        'issued_at',
        'cost',
    	'invoice_period_from',
    	'invoice_period_to',
    	'proceeded_date',
    	'invoice_no',
    	'invoice_tax_amount'
    ];

    protected $dates = [
        'issued_at',
    ];

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            if (auth()->user()) {
                $model->created_by_id = auth()->user()->id;
            }
        });
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
