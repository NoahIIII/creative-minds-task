<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class DeliveryService
{
    public function nearestDeliveriesFilter($lat, $lng, $radius = null)
    {
        return User::delivery()
        ->where('status', 1)
        ->whereNotNull('address_lat')
        ->whereNotNull('address_long')
        ->whereNotNull('phone_verified_at')
        ->select(
            'id',
            'name',
            'thumbnail_image',
            'address_long',
            'address_lat',
            DB::raw("
                ROUND(
                    (6371 * acos(cos(radians($lat))
                    * cos(radians(address_lat))
                    * cos(radians(address_long) - radians($lng))
                    + sin(radians($lat))
                    * sin(radians(address_lat))
                )), 2) AS distance
            ")
        )
        ->when($radius, function ($query) use ($radius) {
            return $query->having('distance', '<=', $radius);
        })
        ->get();

    }
}
