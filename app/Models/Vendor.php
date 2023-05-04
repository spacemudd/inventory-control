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

use App\VendorBank;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use Scottlaurent\Accounting\ModelTraits\AccountingJournal;
use App\Models\Journal;

class Vendor extends Model implements AuditableContract
{
    use Auditable, SoftDeletes, AccountingJournal;

	protected $fillable = [
	    'name',
        'established_at',
        'address',
        'telephone_number',
        'fax_number',
        'email',
        'website',
    ];

	protected $dateFormat = 'Y-m-d H:i:s.u';

	protected $appends = ['link'];

	public function getLinkAttribute()
	{
	    return route('vendors.show', ['id' => $this->id]);
	}

	public function getDisplayNameAttribute()
	{
	    return $this->id. ' - ' . $this->name;
	}

	public function getWebsiteLinkAttribute()
	{
	    $url = $this->website;
        if ($ret = parse_url($url)) {
            if(!isset($ret["scheme"]) )
            {
                $url = 'https://' . $url;
            }
        }

        return $url;
	}

	public function reps()
	{
	    return $this->hasMany(VendorRep::class);
	}

	public function bank()
	{
	    return $this->hasOne(VendorBank::class);
	}

	public function manufacturers()
	{
	    return $this->belongsToMany(Manufacturer::class);
	}

	public function quotations()
	{
	    return $this->hasMany(Quotation::class);
	}

		/**
	 * Morph to Journal.
	 *
	 * @return mixed
	 */
	public function journal()
    {
        return $this->morphOne(Journal::class,'morphed');
    }
	
	/**
	 * Initialize a journal for a given model object
	 *
	 * @param string $currency_code
	 * @return Journal
	 * @throws \Exception
	 */
	public function initJournal($currency_code='USD') {
    	if (!$this->journal) {
	        $journal = new Journal();
	        $journal->currency = $currency_code;
	        $journal->balance = 0;
	        return $this->journal()->save($journal);
	    }
		throw new \Exception('Journal already exists.');
    }
}
