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
        'money',
        'name',
        'note',
        'share',
        'settlement',
        'settlement_at',
    ];

}
