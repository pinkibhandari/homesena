<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Http\Resources\ServiceResource;
use App\Models\TimeSlot;
use App\Models\InstantBookingSetting;

class ServiceController extends Controller
{
    // get all services
    public function getServices()
    {
        $services = Service::with('activeVariants')
            ->where('status', 1)
            ->get();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Service list retrieved successfully',
            'data' => ServiceResource::collection($services)
        ], 200);
    }

    // get service by id
    public function getServiceById($id)
    {
        $service = Service::with('activeVariants')
            ->where('status', 1)
            ->find($id);

        if (!$service) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'Service not found',
                'data' => (object) []
            ]);
        }

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Service retrieved successfully',
            'data' => new ServiceResource($service)
        ]);
    }

    public function timeSlot()
    {
        $allTimeSlots = TimeSlot::select('id', 'start_time')->get();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Time Slots retrieved successfully',
            'data' => $allTimeSlots
        ]);
    }

    public function instantBookingSetting()
    {
        $allduration = InstantBookingSetting::select(
            'id',
            'duration_minutes',
            'price',
            'discount_price'
        )->get();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Instant booking duration retrieved successfully',
            'data' => $allduration
        ]);
    }

    public function create(Request $request)
    { // for testing image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('services', $imageName, 'public');
        }

        $service = Service::create([
            'name' => 'office',
            'image' => $imagePath
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Service created successfully',
            'data' => [
                'id' => $service->id,
                'name' => $service->name,
                'image' => url('storage/' . $service->image)
            ]
        ]);
    }
}
