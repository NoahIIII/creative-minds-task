<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\DeliveryService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function __construct(Private DeliveryService $deliveryService) {}
    /**
     *  get nearest Delivery based on the latitude and longitude
     */
    public function getNearestDeliveries(Request $request)
    {
        // Validate the radius
        $request->validate([
            'radius' => 'nullable|numeric|min:1',
        ]);
        $radius = $request->radius;
        // Fetch the logged-in user location (latitude and longitude)
        $userLat = auth('users')->user()->address_lat;
        $userLng = auth('users')->user()->address_long;

        // start the query to get the nearest delivery
        $nearestDelivery = $this->deliveryService->nearestDeliveriesFilter( $userLat, $userLng, $radius);

        // Return response if no delivery agents found
        if (!$nearestDelivery) {
            return response()->json(['message' => 'No nearby delivery agents found within this radius'], 404);
        }

        // Return the nearest delivery agent data
        return ApiResponseTrait::successResponse(['deliveries'=>$nearestDelivery]);
    }
}
