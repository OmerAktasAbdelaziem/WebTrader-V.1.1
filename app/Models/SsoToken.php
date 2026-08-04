<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SsoToken extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sso_tokens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'token',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Get the client that owns the SSO token.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Scope a query to only include valid (non-expired) tokens.
     */
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now());
    }
}