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

use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;

class PurchaseOrdersLine extends Model implements AuditableContract
{
    use Auditable;

    protected $dates = [
        'received_at',
    ];

    public function received_by()
    {
        return $this->hasOne(User::class, 'id', 'received_by_id');
    }
    
    public function sum()
    {
    	
    }
}
