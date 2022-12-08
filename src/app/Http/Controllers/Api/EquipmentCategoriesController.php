<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EquipmentCategoriesController extends Controller
{
    public function index()
    {
        return $tree = Equipment::get()->toTree();
    }
}
