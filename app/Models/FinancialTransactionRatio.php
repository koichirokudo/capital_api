<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialTransactionRatio extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_group_id',
        'financial_transaction_id',
        'ratio',
    ];

    public function userGroup()
    {
        return $this->belongsTo(UserGroup::class);
    }

    public function financialTransaction()
    {
        return $this->belongsTo(FinancialTransaction::class);
    }
}
