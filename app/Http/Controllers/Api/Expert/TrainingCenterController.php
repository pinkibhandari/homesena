<?php

namespace App\Http\Controllers\Api\Expert;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrainingCenter;

class TrainingCenterController extends Controller
{
    public function trainingCenterList(Request $request)
    {
        $trainingCenters = TrainingCenter::where('status', 1)->get();
        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => $trainingCenters->isEmpty() ? 'No training centers found' : 'Training center list retrieved successfully',
            'data' => $trainingCenters
        ], 200);
    }
}
