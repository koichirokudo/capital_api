<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calculate extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_group_id',
        'date',
        'income',
        'expense',
        'decision',
        'created_at',
        'updated_at',
    ];
}
