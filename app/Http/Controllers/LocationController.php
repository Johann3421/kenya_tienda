<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\District;

class LocationController extends Controller
{
    public function ubigeo(Request $request)
    {
        $ubigeos = District::with('getProvince')->select('id', 'province_id', 'description');
        
        if ($request->distrito) {
            $ubigeos->where('description', 'LIKE', '%'.$request->distrito.'%');
        }

        if ($request->ubigeo) {
            $ubigeos->where('id', $request->ubigeo);
        }

        $ubigeos = $ubigeos->limit(20)->get();

        return $ubigeos;
    }

}
