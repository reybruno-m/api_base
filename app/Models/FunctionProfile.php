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
}
