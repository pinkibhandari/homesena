<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Booking;


class InvoiceController extends Controller
{

    public function generateInvoiceBooking($id)
    {
        $booking = Booking::with(['user', 'slots.expert', 'service', 'address'])->findOrFail($id);

        $pdf = Pdf::loadView('invoice.booking', compact('booking'));

        $fileName = 'invoice_' . $booking->id . '.pdf';
        $path = public_path('invoices/' . $fileName);

        // create folder if not exists
        if (!file_exists(public_path('invoices'))) {
            mkdir(public_path('invoices'), 0777, true);
        }

        // save PDF
        file_put_contents($path, $pdf->output());
        return response()->json([
            'code'=> 200,
            'status' => true,
            'message' => 'Invoice generated successfully',
            'data' => [
                'invoice_url' => asset('invoices/' . $fileName)
            ]
        ]);
    }
}
