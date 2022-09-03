<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activity';

    protected $filliable = [
        'user_id',
        'affected_id',
        'table',
        'address',
        'device',
        'action',
        'description',
    ];

    # Eloquent Relationship
    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
