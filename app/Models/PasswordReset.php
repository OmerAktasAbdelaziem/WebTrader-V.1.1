<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PasswordReset extends Model
{
    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];

    public $timestamps = false;

    /**
     * Check if the token is expired
     * Tokens expire after 1 hour
     */
    public function isExpired()
    {
        return Carbon::parse($this->created_at)->addHour()->isPast();
    }

    /**
     * Delete expired tokens
     */
    public static function deleteExpired()
    {
        return static::where('created_at', '<', Carbon::now()->subHour())->delete();
    }
}
