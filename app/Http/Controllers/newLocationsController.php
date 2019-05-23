<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;

class newLocationsController extends Controller
{
    public function addLocation(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:locations,name'
        ]);

        $locations = Location::create($request->except('_token'));

        return $locations;
    }
}
