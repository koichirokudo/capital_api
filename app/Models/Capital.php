<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capital extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'user_group_id',
        'settlement_id',
        'capital_type',
        'date',
        'financial_transaction_id',
        'money',
        'share',
        'note',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
