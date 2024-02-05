<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaidMethod;

class PaidMethodsController extends Controller
{
    public function index()
    {
        return PaidMethod::all();
    }
}
