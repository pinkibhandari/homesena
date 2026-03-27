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
        $services = Service::with('activeVariants')->where('status', 'ACTIVE')->get();
        if ($services->isEmpty()) {
            // if(empty($services)) {
            return response()->json([
                'code' => 422,
                'data' => (object) [],
                'status' => false,
                'message' => 'No services found'
            ], 422);
        } else {
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'Service List retrieved successfully',
                'data' => ServiceResource::collection($services)
            ], 200);

        }
    }

    // get service by id
    public function getServiceById($id)
    {
        $service = Service::with('activeVariants')->where('status', 'ACTIVE')->find($id);
        if (!$service) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'data' => (object) [],
                'message' => 'Service not found'
            ]);
        } else {
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'Service retrieved successfully',
                'data' => new ServiceResource($service)
            ]);
        }
    }

    public function timeSlot()
    {
        $allTimeSlots = TimeSlot::select('id', 'start_time')->get();
        if ($allTimeSlots) {
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'Time Slots retrieved successfully',
                'data' => $allTimeSlots
            ]);
        } else {
            return response()->json([
                'status' => false,
                'code' => 422,
                'data' => (object) [],
                'message' => 'Time Slots not found'
            ]);
        }

    }

    public function instantBookingSetting(){
       $allduration = InstantBookingSetting:: select( 'id','duration_minutes',	'price','discount_price',)->get();
        if ($allduration) {
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'Instant booking duration retrieved successfully',
                'data' => $allduration
            ]);
        } else {
            return response()->json([
                'status' => false,
                'code' => 422,
                'data' => (object) [],
                'message' => 'Instant Bboking duration Slots not found'
            ]);
        }
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
