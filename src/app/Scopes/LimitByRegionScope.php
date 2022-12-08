<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class LimitByRegionScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        $perm = auth()->user()->hasPermissionTo('view-all-regions');
        if (!$perm) {
            $builder->where('region_id', auth()->user()->region_id);
        }
    }
}
