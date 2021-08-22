<?php

namespace App\Http\Controllers\rest;

use App\Http\Controllers\Controller;
use Validator;
use DB;
use Illuminate\Http\Request;

class RerstuarantController extends Controller
{

    //取得所有餐廳
    public function getRestuarants(Request $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $level = $request->input('level');

        $latMin = $lat - ($level+1)*(0.1);
        $latMax = $lat + ($level+1)*(0.1);

        $lngMin = $lng - ($level+1)*(0.1);
        $lngMax = $lng + ($level+1)*(0.1);

        //$latMin = $lat;
        //$lngMin = $lng;

        $restaurant = DB::table('restaurant')
                    ->whereBetween('latitudes', [$latMin, $latMax])
                    ->whereBetween('longitudes', [$lngMin, $lngMax])
                    ->get();
        //包裝成JSON格式回傳
        $response = [
            'success' => true,
            'latMin' => $latMin,
            'latMax' => $latMax,
            'lngMin' => $lngMin,
            'lngMax' => $lngMax,
            'count' => count($restaurant),
            'restaurant' => $restaurant,
            
        ];
        return response()->json($response);
    }

}
