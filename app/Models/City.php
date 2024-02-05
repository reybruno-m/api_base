<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $table = 'cities';

    protected $filliable = ['name','province_id'];

    # Eloquent Relationship
    public function province(){
        return $this->belongsTo('App\Models\Province');
    }

}
