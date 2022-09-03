<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FunctionProfile extends Model
{
    use HasFactory;

    protected $table = 'functions_profiles';

    protected $filliable = [
        'function_id',
        'profile_id',
    ];

    public function profile(){
        return $this->belongsTo('App\Models\Profile', 'profile_id');
    }

    public function functions(){
        return $this->belongsTo('App\Models\Functions', 'function_id');
    }
}
