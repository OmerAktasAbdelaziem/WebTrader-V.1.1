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
        'credit_card_details',
        'note',
    ];

    protected $casts = [
        'bank_details' => 'array',
        'crypto_details' => 'array',
        'credit_card_details' => 'array',
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

    /**
     * Get normalized status value
     */
    public function getNormalizedStatusAttribute()
    {
        $status = strtolower(trim($this->status ?? ''));
        
        if (in_array($status, ['approved', 'accepted', 'completed', '1', 'success'])) {
            return 'approved';
        } elseif (in_array($status, ['pending', 'processing', 'waiting', '0', 'submitted', '']) || is_null($this->status)) {
            return 'pending';
        } elseif (in_array($status, ['rejected', 'denied', 'cancelled', 'failed', '-1'])) {
            return 'rejected';
        }
        
        return 'pending'; // Default fallback
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        switch ($this->normalized_status) {
            case 'approved':
                return 'bg-success';
            case 'rejected':
                return 'bg-danger';
            default:
                return 'bg-warning text-dark';
        }
    }

    /**
     * Get status display text
     */
    public function getStatusDisplayAttribute()
    {
        switch ($this->normalized_status) {
            case 'approved':
                return __('web.accepted');
            case 'rejected':
                return __('web.rejected');
            default:
                return __('web.pending');
        }
    }


}
