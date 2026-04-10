<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\HelpSupportContact;

class HelpSupportContactController extends Controller
{
 public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name'    => 'required|string|max:100',
        'email'   => 'nullable|email',
        'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/',
        'subject' => 'nullable|string|max:150',
        'message' => 'required|string|max:1000',
        'file' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'code' => 422,
            'message' => $validator->errors()->first(),
            'data' => (object)[]
        ], 422);
    }
        $path = null;
        $type = null;

        if ($request->hasFile('file')) {

            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'_'.$file->getClientOriginalName();

            if (in_array($ext, ['jpg','jpeg','png'])) {
                $file->move(public_path('uploads/help/images'), $filename);
                $path = 'uploads/help/images/'.$filename;
                $type = 'image';

            } elseif (in_array($ext, ['mp4','mov','avi'])) {
                $file->move(public_path('uploads/help/videos'), $filename);
                $path = 'uploads/help/videos/'.$filename;
                $type = 'video';
            }
        }
    $contact = HelpSupportContact::create([
        'user_id' => auth()->id(),
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'subject' => $request->subject,
        'message' => $request->message,
    ]);

    return response()->json([
        'status' => true,
        'code' => 200,
        'message' => 'Message sent successfully',
        'data' => $contact
    ]);
}
 }


