<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;

class RegionsController extends Controller
{
    public function getRegions()
    {
        return Region::select('id', 'name')->get();
    }
}
