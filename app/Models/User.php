<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'position',
        'level',
        'company_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class,'company_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class,'user_id');
    }

    public function holidayList(): HasMany
    {
        return $this->hasMany(Holiday::class,'user_id');
    }
    public function hours(): HasMany
    {
        return $this->hasMany(Hour::class,'user_id');
    }
    public function locations(): HasMany
    {
        return $this->hasMany(Location::class,'user_id');
    }
    public function technical_reports(): HasMany
    {
        return $this->hasMany(TechnicalReport::class,'user_id');
    }
    public function business_hours(): HasMany
    {
        return $this->hasMany(BusinessHour::class,'user_id');
    }

}
