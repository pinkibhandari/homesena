<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
class InvoiceController extends Controller
{
    
     public function bookingInvoice($bookingId)
    {
        $booking = Booking::with(['user', 'slots.expert', 'service', 'address'])->find($bookingId);
        if (!$booking) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Booking not found',
                'data' => (object) []
            ]);
        }
        if ($booking->invoice && file_exists(public_path($booking->invoice->file_path))) {
            // $invoiceUrl = app()->environment('local')
            //     ? asset($booking->invoice->file_path)
            //     : url('public/' . $booking->invoice->file_path); 
            $invoiceUrl = public_path($booking->invoice->file_path);

               return response()->download($invoiceUrl);
            }
        $invoice_issued_date = now();
        $amount = $booking->total_price;
        // $invoiceNumber = 'INV-BKG-' . date('Ymd') . '-' . $booking->id;
        $invoiceNumber = 'HSS' . date('Ymd') . str_pad($booking->id, 4, '0', STR_PAD_LEFT);
        $pdf = Pdf::loadView('invoice.booking', compact('booking', 'invoiceNumber', 'amount', 'invoice_issued_date'));
        $fileName = 'booking_' . $booking->id . '.pdf';
        $path = 'invoices/' . $fileName;
        if (!file_exists(public_path('invoices'))) {
            mkdir(public_path('invoices'), 0777, true);
        }
        file_put_contents(public_path($path), $pdf->output());
        //  morph save
         Invoice::create([
            'booking_id' => $booking->id,
            'invoice_number' => $invoiceNumber,
            'amount' => $amount,
            'file_path' => $path,
            'issued_at' => $invoice_issued_date,
        ]);
        // $invoiceUrl = app()->environment('local')
        //     ? asset($path)
        //     : url('public/' . $path);

        $invoiceUrl = public_path($path);
         return response()->download($invoiceUrl);
    }

}