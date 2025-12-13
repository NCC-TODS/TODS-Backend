<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientVersion extends Model
{
    protected $fillable = [
        'version',
        'file_path',
        'is_active',
    ];
}
