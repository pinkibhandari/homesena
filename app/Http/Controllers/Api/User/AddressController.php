<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Address;
use App\Models\Booking;

class AddressController extends Controller
{
    //  save auth user address
    public function saveAddress(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'User not found',
                'data' => (object) []
            ], 422);
        }
        $validator = Validator::make($request->all(), [
            'address' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => $validator->errors()->first(),
                'data' => (object) []
            ], 422);
        }
        $addressText = strtolower(trim($request->address));
        $flatNo = $request->flatNo ? strtolower(trim($request->flatNo)) : null;
        // Check duplicate address
        $query = Address::where('user_id', $user->id)
            ->where('address', $addressText);
        if ($flatNo) {
            $query->where('flat_no', $flatNo);
        } else {
            $query->where(function ($q) {
                $q->whereNull('flat_no')
                    ->orWhere('flat_no', '');
            });
        }
        if ($query->exists()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'Address already exists',
                'data' => (object) []
            ], 422);
        }
        // Create address
        $address = Address::create([
            'user_id' => $user->id,
            'address' => $addressText,
            'flat_no' => $flatNo,
            'landmark' => $request->Landmark,
            'save_as' => $request->saveAs,
            'pets' => $request->Pets,
            'address_lat' => $request->addressLat,
            'address_long' => $request->addressLong,
        ]);

        if (!$address) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Failed to save address',
                'data' => (object) []
            ], 422);
        }
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Address saved successfully',
            'data' => $address
        ], 200);
    }

    // update address auth user address
    public function updateAddress(Request $request, $id)
    {
        $address = Address::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();
        if (!$address) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Address not found',
                'data' => (object) []
            ], 422);
        }
        $validator = Validator::make($request->all(), [
            'address' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => $validator->errors()->first(),
                'data' => (object) []
            ], 422);
        }
        $addressText = strtolower(trim($request->address));
        $flatNo = $request->flatNo ? strtolower(trim($request->flatNo)) : null;
        // Duplicate check
        $query = Address::where('user_id', auth()->id())
            ->where('address', $addressText)
            ->where('id', '!=', $id);
        if ($flatNo) {
            $query->where('flat_no', $flatNo);
        } else {
            $query->where(function ($q) {
                $q->whereNull('flat_no')
                    ->orWhere('flat_no', '');
            });
        }
        if ($query->exists()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'Address already exists',
                'data' => (object) []
            ], 422);
        }
        $updated = $address->update([
            'address' => $addressText,
            'flat_no' => $flatNo,
            'landmark' => $request->Landmark,
            'save_as' => $request->saveAs,
            'pets' => $request->Pets,
            'address_lat' => $request->addressLat,
            'address_long' => $request->addressLong,
        ]);
        if (!$updated) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Failed to update address',
                'data' => (object) []
            ], 422);
        }
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Address updated successfully',
            'data' => $address->fresh()
        ], 200);
    }

    // auth all address list
    public function addressList(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'code' => 422,
                'data' => (object) [],
                'status' => false,
                'message' => 'User not found'
            ], 422);
        } else {
            $addresses = $user->addresses()->latest()->get();
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'Address list retrieved successfully',
                'data' => $addresses
            ]);
        }
    }


    public function deleteAddress(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'User not found',
                'data' => (object) []
            ], 422);
        }
        $address = Address::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
        if (!$address) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Address not found',
                'data' => (object) []
            ], 422);
        }

        $bookingExists = Booking::where('address_id', $id)->exists();

        if ($bookingExists) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Address cannot be deleted because it is used in bookings',
                'data' => (object) []
            ]);
        }
        $deleted = $address->delete();
        if (!$deleted) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Failed to delete address',
                'data' => (object) []
            ], 422);
        }
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Address deleted successfully',
            'data' => (object) []
        ], 200);
    }
}
