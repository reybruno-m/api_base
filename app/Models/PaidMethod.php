<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaidMethod extends Model
{
    use HasFactory;

    protected $table = 'paid_methods';

    protected $filliable = [
        'name',
    ];
}
