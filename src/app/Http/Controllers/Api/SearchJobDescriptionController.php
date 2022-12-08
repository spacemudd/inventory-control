<?php

namespace App\Http\Controllers\Api;

use App\Models\JobOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class SearchJobDescriptionController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string',
        ]);

        $jo = JobOrder::where('job_description', 'LIKE', $request->q.'%')->first();

        if ($jo) {
            $description = Str::replaceFirst($request->q, '', $jo->job_description);
        } else {
            $description = '';
        }

        return response()->json([
            'suggestion' => $description,
        ]);
    }
}
