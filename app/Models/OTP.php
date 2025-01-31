<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    use HasFactory;

    protected $table = 'otps';

    protected $fillable = [
        'phone',
        'code',
        'expires_at',
    ];
    protected $casts = [
        'code' => 'hashed',
        'expires_on' => 'datetime:Y-m-d H:i:s',
    ];
}
