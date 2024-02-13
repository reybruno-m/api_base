<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $table = 'files';

    protected $filliable = [
        'origin_table',
        'origin_id',
        'file',
        'description',
    ];

    /* public function getFileAttribute(): string
    {
        return Storage::disk('comprobantes')->url($this->file);
    } */
}