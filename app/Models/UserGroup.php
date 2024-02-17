<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    use HasFactory;
    protected $fillable = [
        'group_name',
        'invite_code',
        'invite_limit',
        'start_day',
        'created_at',
        'updated_at',
    ];
}
