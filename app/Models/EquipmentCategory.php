<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class EquipmentCategory extends Model
{
    use NodeTrait;

    protected $fillable = [
        'name',
    ];
}
