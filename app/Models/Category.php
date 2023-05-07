<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use NodeTrait;

    protected $fillable = [
        'name',
    ];

    protected $dateFormat = 'Y-m-d H:i:s';

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
