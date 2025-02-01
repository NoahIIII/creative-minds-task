<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'phone_verified_at',
        'password',
        'profile_image',
        'thumbnail_image',
        'status',
        'user_type',
        'address_lat',
        'address_long',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'boolean',
    ];

    /**
     * Accessor to get the thumbnail image url.
     *
     * @param  string  $value
     * @return string
     */
    public function getThumbnailImageAttribute($value)
    {
        if ($value) {
            return asset('storage/images/' . $value);
        }
        return null;
    }
    /**
     * Accessor to get the profile image url.
     *
     * @param  string  $value
     * @return string
     */
    public function getProfileImageAttribute($value)
    {
        if ($value) {
            return asset('storage/images/' . $value);
        }
        return null;
    }
    public function getFormattedCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

    // You can create a similar accessor for any other date attribute
    public function getFormattedUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }
    /**
     * User Type Scope
     * @param mixed $query
     */
    public function scopeDelivery($query)
    {
        return $query->where('user_type', 'delivery');
    }
    public function fcmTokens()
    {
        return $this->hasMany(FcmToken::class);
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
