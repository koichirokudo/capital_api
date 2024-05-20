<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettlementDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'settlement_id',
        'payer_id',
        'payee_id',
        'amount',
        'created_at',
        'updated_at',
    ];
}
