<?php

namespace App\Http\Controllers\Api;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LocationsController extends Controller
{
    /**
     *
     * @return mixed
     */
    public function index()
    {
        return Location::get();
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:locations,name,'.$request->id,
        ]);

        $loc = Location::findOrFail($request->id);
        $loc->update($request->except('_token'));

        return $loc;
    }

    /**
     *
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        Location::findOrFail($id)->delete();
        return [
            'msg' => 'success',
        ];
    }
}
