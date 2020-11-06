<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentDisabled extends Equipment
{
    protected $appends = [
        'dragDisabled',
        'addTreeNodeDisabled',
        'addLeafNodeDisabled',
        'editNodeDisabled',
        'delNodeDisabled',
    ];

    public function getdragDisabledAttribute()
    {
        return true;
    }

    public function getaddTreeNodeDisabledAttribute()
    {
        return true;
    }

    public function getaddLeafNodeDisabledAttribute()
    {
        return true;
    }

    public function geteditNodeDisabledAttribute()
    {
        return true;
    }

    public function getdelNodeDisabledAttribute()
    {
        return true;
    }
}
