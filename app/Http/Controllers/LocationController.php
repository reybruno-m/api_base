<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\City;
use App\Models\Country;
use App\Models\Province;

class LocationController extends Controller
{
    public function locations()
    {

        $provinces_cities = Province::with('cities')->get();
        $countries = Country::get();

        return response()->json([
            'status' => 'success',
            'provinces_cities' => $provinces_cities,
            'countries' => $countries,
        ], 200);
    }

    public function locationsAutocomplete($term)
    {
        $term = strtolower($term);

        if(strlen($term) < 2){
            return response([], 200);
        }

        $result = City::with('province')
            ->where('name', 'like', $term.'%')
            ->orderBy('name', 'asc')
            ->get();

        return response($result, 200);
    }
}
