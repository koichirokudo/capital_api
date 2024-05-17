<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class settlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_group_id',
        'year',
        'month',
        'settled',
        'created_at',
        'updated_at',
    ];
}
