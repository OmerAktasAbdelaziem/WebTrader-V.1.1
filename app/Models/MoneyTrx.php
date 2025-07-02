<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneyTrx extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_details',
        'broker_id',
        'bank_id',
        'comment',
        'amount',
        'type',
        'status',
        'method',
        'usdt',
        'receipt',
        'crypto_details',
        'credit_card_details'
    ];

    protected $casts = [
        'bank_details' => 'array',
        'crypto_details' => 'array',
        'credit_card_details' => 'array',
        'usdt'         => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class,'broker_id','broker_id');
    }

    public function bank_details()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function money_trxs()
    {
        return $this->hasMany(MoneyTrx::class, 'bank_id');
    }


}
