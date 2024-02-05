<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $table = 'provinces';

    protected $filliable = ['name'];

    # Eloquent Relationship
    public function cities(){
        return $this->hasMany('App\Models\City', 'province_id');
    }
}
