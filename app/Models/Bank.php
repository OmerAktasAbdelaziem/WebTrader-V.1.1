<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'beneficiary_country',
        'beneficiary_address',
        'aba_routing_number',
        'beneficiary_name',
        'account_number',
        'swift_code',
        'is_active',
        'currency',
        'address',
        'country',
        'type',
        'name',
        'iban',
        'bic',
    ];

    public function moneyTrx()
    {
        return $this->hasMany(MoneyTrx::class);
    }
}
