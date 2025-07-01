<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class Client extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'clients';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $fillable = [
        'favourite_assets',
        'appointment_date',
        'last_captured_at',
        'smart_user_name',
        'is_have_invest',
        'remember_token',
        'asset_group_id',
        'smart_user_id',
        'is_have_money',
        'password_text',
        'sales_status',
        'is_have_time',
        'company_name',
        'account_type',
        'first_owner',
        'assigned_at',
        'pipeline_id',
        'is_notified',
        'first_name',
        'deleted_at',
        'ftd_amount',
        'created_by',
        'smart_data',
        'created_at',
        'renewed_at',
        'ftd_bonus',
        'last_name',
        'broker_id',
        'how_money',
        'is_online',
        'username',
        'ark_data',
        'password',
        'reg_date',
        'ftd_date',
        'campaign',
        'is_renew',
        'loggedAt',
        'user_id',
        'deleted',
        'country',
        'message',
        'options',
        'phone1',
        'source',
        'phone2',
        'is_ftd',
        'gender',
        'is_25',
        'email',
        'usdt',
        'age',
        'id',
        'ad',
        'registeration_ip',

    ];

    protected $casts = [
        'favourite_assets' => 'array',
        'reg_date'         => 'datetime',
        'options'          => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (Auth::check()) {
                $model->pipeline_id = Auth::user()->pipeline_id;
            }
        });
    }

    public function newEloquentBuilder($query)
    {
        $builder = parent::newEloquentBuilder($query);

        if (Auth::check()) {
            $builder->where('pipeline_id', Auth::user()->pipeline_id);
        }

        return $builder;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function actions()
    {
        return $this->hasMany(Action::class, 'client_id');
    }

    public function comments()
    {
        return $this->hasMany(Client_comment::class, 'client_id');
    }
    
    public function document()
    {
        return $this->hasOne(ClientDocument::class, 'client_id');
    }

    public function ark_accounts()
    {
        return $this->hasMany(ArkAccount::class, 'client_id');
    }

    public function firstOwner()
    {
        return $this->belongsTo(User::class, 'first_owner');
    }

    public function marketing_email_logs()
    {
        return $this->hasMany(MarketingEmailLog::class, 'client_id');
    }

    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class);
    }
    
    // Accessor for full name
    public function getNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }
    
    // Accessor for phone
    public function getPhoneAttribute()
    {
        return $this->phone1 ?: $this->phone2;
    }
}
