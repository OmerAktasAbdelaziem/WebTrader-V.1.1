<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'sell_commission',
        'buy_commission',
        'is_percentage',
        'ask_spread',
        'bid_spread',
        'is_active',
        'currency',
        'leverage',
        'symbol',
        'type',
        'name',
        'size',
    ];

    protected $casts=[
        'is_percentage' => 'array',
        'leverage'      =>'array',
        'size'          =>'array',
    ];
}
