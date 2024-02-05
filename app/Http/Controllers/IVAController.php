<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Iva;

class IVAController extends Controller
{
    public function index()
    {
        return Iva::all();
    }
}
