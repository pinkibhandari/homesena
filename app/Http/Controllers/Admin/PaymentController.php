<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment; // ✅ yaha change

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where('gateway_payment_id', 'like', "%$search%")
                  ->orWhere('gateway_order_id', 'like', "%$search%");
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->latest()
            ->paginate(10);

        return view('admin.payments.index', compact('payments'));
    }

   

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment deleted successfully.');
    }
}