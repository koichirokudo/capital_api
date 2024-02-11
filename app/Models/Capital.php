<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capital extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'capital_type',
        'expenses_item',
        'date',
        'group_id',
        'user_id',
        'money',
        'note',
        'share',
        'settlement',
        'settlement_at',
    ];

}
