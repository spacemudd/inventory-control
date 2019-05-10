<?php

namespace App\Http\Controllers\Api;

use App\Models\MaterialRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MaterialRequestsController extends Controller
{
    public function indexWithApprovedItems($materialNumber)
    {
        return MaterialRequest::approved()->with('items')->where('number', $materialNumber)->get();
    }
}
