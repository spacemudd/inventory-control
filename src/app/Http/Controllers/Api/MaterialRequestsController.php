<?php

namespace App\Http\Controllers\Api;

use App\Models\MaterialRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MaterialRequestItem;

class MaterialRequestsController extends Controller
{
    public function indexWithApprovedItems($materialNumber)
    {
        return MaterialRequest::with(['items' => function($q) {
            $q->with('last_template', 'last_quoted');
        }])
            ->where('number', $materialNumber)->get();
    }

    public function indexPending()
    {
        return MaterialRequest::pending()->get();
    }
}
