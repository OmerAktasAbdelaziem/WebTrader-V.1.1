<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'required_margin',
        'ref_currency',
        'close_price',
        'open_price',
        'created_at',
        'closed_at',
        'broker_id',
        'currency',
        'comment',
        'amount',
        'status',
        'type',
        'pnl',
        's_l',
        's_p',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'broker_id', 'broker_id');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'currency', 'id');
    }
}
