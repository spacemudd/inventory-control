<?php

namespace App\Http\Controllers\Api;

use App\Models\MaterialRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MaterialRequestItem;

class MaterialRequestsController extends Controller
{
    public function indexWithApprovedItems()
    {

        $response = MaterialRequest::approved()->with('items')->get();

        return $response;
    }
}
