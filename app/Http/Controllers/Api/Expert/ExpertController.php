<?php

namespace App\Http\Controllers\Api\Expert;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpertDetail;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ExpertDetailResource;
use App\Models\ExpertOnlineLog;
class ExpertController extends Controller
{
    public function storeDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'training_center_id' => 'required|exists:training_centers,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => $validator->errors()->first(),
                'data' => (object) [],
            ], 422);
        }
        $expert = ExpertDetail::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'training_center_id' => $request->training_center_id
            ]
        );
        if ($expert) {
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'Expert Detail created successfully',
                'data' => new ExpertDetailResource($expert)
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Expert Detail not created',
                'code' => 422,
                'data' => (object) []
            ], 422);
        }
    }



    public function isOnlineStatusUpdate(Request $request)
    {
        $request->validate([
            'is_online' => 'required|boolean',
        ]);
        $expert = ExpertDetail::where('user_id', auth()->id())->first();
        if (!$expert) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'Expert not found',
                'data' => (object) []
            ], 422);
        }
        $newStatus = $request->is_online;
        $oldStatus = $expert->is_online;
        //  Update status
        $expert->is_online = $newStatus;
        $expert->save();
        //  Only log if status changed
        if ($newStatus && !$oldStatus) {
            //  Going ONLINE
            ExpertOnlineLog::create([
                'user_id' => auth()->id(),
                'online_at' => now()
            ]);
            $message = 'You are now online';
        } elseif (!$newStatus && $oldStatus) {
            //  Going OFFLINE
            $log = ExpertOnlineLog::where('user_id', auth()->id())
                ->whereNull('offline_at')
                ->latest()
                ->first();
            if ($log) {
                $log->update([
                    'offline_at' => now()
                ]);
            }
            $message = 'You are now offline';
        } else {
            $message = $newStatus ? 'Already online' : 'Already offline';
        }
        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => $message,
            'data' => [
                'is_online' => $expert->is_online
            ]
        ]);
    }

}
