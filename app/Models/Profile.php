<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles';

    protected $filliable = [
        'name',
        'description',
    ];

    # Obtiene las funciones que posee asociada este perfil
    public function functions(){
        return $this->hasMany('App\Models\FunctionProfile', 'profile_id');
    }
}
