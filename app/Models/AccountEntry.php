<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountEntry extends Model
{
    use HasFactory;

    protected $table = 'account_entries';

    protected $filliable = [
        'type_id',
        'date',
        'amount',
        'concept',
        'state_id',
        'paid_method_id',
        'origin_id',
        'expiration_2',
        'expiration_1',
        'file_name',
        'user_id',
    ];
}
