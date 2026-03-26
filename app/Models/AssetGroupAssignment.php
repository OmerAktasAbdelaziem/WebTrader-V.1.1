<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetGroupAssignment extends Model
{
    protected $table = 'asset_group_assignments';

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset', 'id');
    }
}