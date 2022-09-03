<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Functions extends Model
{
    use HasFactory;

    protected $table = 'functions';

    protected $filliable = [
        'name',
        'description',
        'url',
    ];

    # Obtiene los perfiles a los cuales pertenece esta función.
    public function profile(){
        return $this->hasMany('App\Models\FunctionProfile', 'function_id'/*, 'profile_id' */);
    }
}
