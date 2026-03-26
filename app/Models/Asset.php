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

    // حذفنا casts الخاصة بالقيم القادمة من assignments
    protected $casts = [
        // اترك فقط ما هو فعلاً JSON داخل جدول assets
    ];

    public function assignments()
    {
        return $this->hasMany(AssetGroupAssignment::class, 'asset', 'id');
    }

    /**
     * Leverage per group
     */
    public function getLeverageAttribute()
    {
        return $this->relationLoaded('assignments')
            ? $this->assignments->pluck('leverage', 'asset_group')
            : $this->assignments()->pluck('leverage', 'asset_group');
    }

    /**
     * Size per group
     */
    public function getSizeAttribute()
    {
        return $this->relationLoaded('assignments')
            ? $this->assignments->pluck('size', 'asset_group')
            : $this->assignments()->pluck('size', 'asset_group');
    }

    /**
     * is_percentage per group
     */
    public function getIsPercentageAttribute()
    {
        return $this->relationLoaded('assignments')
            ? $this->assignments->pluck('is_percentage', 'asset_group')
            : $this->assignments()->pluck('is_percentage', 'asset_group');
    }
}