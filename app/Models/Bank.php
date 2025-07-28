<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency',
        'country',
        'type',
        'name',
        'address',
        'beneficiary_name',
        'beneficiary_country',
        'beneficiary_address',
        'aba_routing_number',
        'iban',
        'swift_code',
        'account_number',
        'bic',
        'is_active',
        'pipeline_id',
    ];

    public function moneyTrx()
    {
        return $this->hasMany(MoneyTrx::class);
    }
}
