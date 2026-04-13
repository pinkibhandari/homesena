<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\UserSupport;
use Illuminate\Support\Facades\File;

class UserSupportController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'nullable|email',
            'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/',
            'subject' => 'nullable|string|max:150',
            'message' => 'required|string|max:1000',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,webp,mp4,mov,avi,pdf|max:20480'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => $validator->errors()->first(),
                'data' => (object) []
            ], 422);
        }
        $path = null;
        $type = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $ext = strtolower($file->getClientOriginalExtension());
            $filename = uniqid() . '_' . $file->getClientOriginalName();
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $folder = public_path('uploads/help/images');
                if (!File::exists($folder)) {
                    File::makeDirectory($folder, 0777, true, true);
                }
                $file->move($folder, $filename);
                $path = 'uploads/help/images/' . $filename;
                $type = 'image';
            } elseif (in_array($ext, ['mp4', 'mov', 'avi'])) {
                $folder = public_path('uploads/help/videos');
                if (!File::exists($folder)) {
                    File::makeDirectory($folder, 0777, true, true);
                }
                $file->move($folder, $filename);
                $path = 'uploads/help/videos/' . $filename;
                $type = 'video';
            } elseif ($ext === 'pdf') {
                $folder = public_path('uploads/help/documents');
                if (!File::exists($folder)) {
                    File::makeDirectory($folder, 0777, true, true);
                }
                $file->move($folder, $filename);
                $path = 'uploads/help/documents/' . $filename;
                $type = 'pdf';   
            }
        }
        $contact = UserSupport::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'file' => $path,
            'type' => $type
        ]);
        $contact->file = $path ? asset('public/' . $path) : null;
        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => 'Message sent successfully',
            'data' => $contact
        ]);
    }
}


