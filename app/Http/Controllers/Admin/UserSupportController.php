<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserSupport;

class UserSupportController extends Controller
{
    /**
     *  Listing Page
     */
    public function index()
    {
        $totalTickets   = UserSupport::count();
        $pendingTickets = UserSupport::where('status', 'pending')->count();
        $resolvedTickets = UserSupport::where('status', 'resolved')->count();

        $pendingSupports = UserSupport::where('status', 'pending')->latest()->get();
        $resolvedSupports = UserSupport::where('status', 'resolved')->latest()->get();

        return view('admin.user_supports.index', compact(
            'totalTickets',
            'pendingTickets',
            'resolvedTickets',
            'pendingSupports',
            'resolvedSupports'
        ));
    }

    /**
     *  Show Details Page
     */
    public function show($id)
    {
        $support = UserSupport::findOrFail($id);

        return view('admin.user_supports.show', compact('support'));
    }

    /**
     *  Store (Frontend Ticket Submit)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:10000'
        ]);

        $fileName = null;

        if ($request->hasFile('file')) {
            $fileName = time() . '.' . $request->file->extension();
            $request->file->move(public_path('uploads/support'), $fileName);
        }

        UserSupport::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'file' => $fileName,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Ticket submitted successfully');
    }

    /**
     *  Resolve Ticket (AJAX)
     */
    public function update(Request $request, $id)
    {
        try {
            $support = UserSupport::find($id);

            if (!$support) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ticket not found'
                ]);
            }

            if ($support->status === 'resolved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Already resolved'
                ]);
            }

            //  Update status
            $support->status = 'resolved';
            $support->save();

            return response()->json([
                'success' => true,
                'message' => 'Ticket resolved successfully',

                //  frontend ke liye data
                'data' => [
                    'id' => $support->id,
                    'name' => $support->name,
                    'email' => $support->email,
                    'phone' => $support->phone,
                    'message' => $support->message,
                    'file' => $support->file,
                    'updated_at' => $support->updated_at->format('d M Y h:i A'),
                    'view_url' => route('admin.user_supports.show', $support->id)
                ]
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ]);
        }
    }

    /**
     *  (Optional)
     */
    public function create() {}
    public function edit($id) {}
    public function destroy($id) {}
}