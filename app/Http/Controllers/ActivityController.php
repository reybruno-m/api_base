<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Browser;

class ActivityController extends Controller
{
    public function __construct()
    {
       // $this->middleware('auth:api');
    }

    public function index()
    {

    }

    public function store($table, $action = "", $description = "")
    {
        $user = auth()->user();

        $record = new Activity();
        $record->user_id = $user ? $user->id : 1;
        $record->table = $table;
        $record->address = $_SERVER['REMOTE_ADDR'];
        $record->device = Browser::platformFamily()	." ". Browser::platformVersion();
        $record->action = $action;
        $record->description = $description;
        $record->save();
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
