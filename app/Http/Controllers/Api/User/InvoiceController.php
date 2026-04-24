<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Booking;
use App\Models\BookingSlot;


class InvoiceController extends Controller
{

    // public function generateInvoiceBooking($id)
    // {
    //     $booking = Booking::with(['user', 'slots.expert', 'service', 'address'])->findOrFail($id);
    //     $pdf = Pdf::loadView('invoice.booking', compact('booking'));
    //     $fileName = 'invoice_' . $booking->id . '.pdf';
    //     $path = public_path('invoices/' . $fileName);
    //     // create folder if not exists
    //     if (!file_exists(public_path('invoices'))) {
    //         mkdir(public_path('invoices'), 0777, true);
    //     }
    //     // save PDF
    //     file_put_contents($path, $pdf->output());
    //     return response()->json([
    //         'code'=> 200,
    //         'status' => true,
    //         'message' => 'Invoice generated successfully',
    //         'data' => [
    //             'invoice_url' => asset('invoices/' . $fileName)
    //         ]
    //     ]);
    // }


    // SLOT INVOICE
    public function generateSlotInvoice($slotId)
    {
        $slot = BookingSlot::with('booking.user','booking.address' ,'booking.service')->find($slotId);
        if (!$slot) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Slot not found',
                'data' => (object) []
            ]);
        }
        //  check existing
        if ($slot->invoice && file_exists(public_path($slot->invoice->file_path))) {
             $invoiceUrl = app()->environment('local')
                ? asset($slot->invoice->file_path)
                : url('public/' . $slot->invoice->file_path); 
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'Already generated',
                'data' => [
                    'invoice_url' => $invoiceUrl
                ]
            ]);
        }
        $amount = $slot->amount;
        // $invoiceNumber = 'INV-SLOT-' . date('Ymd') . '-' . $slot->id;
        $invoiceNumber = 'INV-SLOT-' . date('Ymd') . '-' . str_pad($slot->id, 4, '0', STR_PAD_LEFT);
        $pdf = Pdf::loadView('invoice.slot', compact('slot', 'invoiceNumber', 'amount'));
        $fileName = 'slot_' . $slot->id . '.pdf';
        $folder = public_path('invoices');
        $path = 'invoices/' . $fileName;
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }
        file_put_contents(public_path($path), $pdf->output());
        //  morph save
        $slot->invoice()->create([
            'invoice_number' => $invoiceNumber,
            'type' => 'slot',
            'amount' => $amount,
            'file_path' => $path,
            'issued_at' => now(),
        ]);
        $invoiceUrl = app()->environment('local')
            ? asset($path)
            : url('public/' . $path);
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Invoice generated successfully',
            'data' => [
                'invoice_url' => $invoiceUrl
            ]
        ]);
    }

    // BOOKING INVOICE
    public function generateBookingInvoice($bookingId)
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
            $invoiceUrl = app()->environment('local')
                ? asset($booking->invoice->file_path)
                : url('public/' . $booking->invoice->file_path);    
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'Already generated',
                'data' => [
                    'invoice_url' => $invoiceUrl
                ]
            ]);
        }
        $amount = $booking->total_amount;
        // $invoiceNumber = 'INV-BKG-' . date('Ymd') . '-' . $booking->id;
        $invoiceNumber = 'INV-BKG-' . date('Ymd') . '-' . str_pad($booking->id, 4, '0', STR_PAD_LEFT);
        $pdf = Pdf::loadView('invoice.booking', compact('booking', 'invoiceNumber', 'amount'));
        $fileName = 'booking_' . $booking->id . '.pdf';
        $path = 'invoices/' . $fileName;
        if (!file_exists(public_path('invoices'))) {
            mkdir(public_path('invoices'), 0777, true);
        }
        file_put_contents(public_path($path), $pdf->output());
        //  morph save
        $booking->invoice()->create([
            'invoice_number' => $invoiceNumber,
            'type' => 'booking',
            'amount' => $amount,
            'file_path' => $path,
            'issued_at' => now(),
        ]);
        $invoiceUrl = app()->environment('local')
            ? asset($path)
            : url('public/' . $path);
        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => 'Invoice generated successfully',
            'data' => [
                'invoice_url' => $invoiceUrl
            ]
        ]);
    }
}
