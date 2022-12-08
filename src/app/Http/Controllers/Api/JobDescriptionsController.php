<?php

namespace App\Http\Controllers\Api;

use App\Models\JobOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class JobDescriptionsController extends Controller
{
    public function index()
    {
        return DB::table('job_orders')->select('job_description')->distinct()->get();
    }
}
