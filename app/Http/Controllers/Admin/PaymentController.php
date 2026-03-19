<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function index()
    {
        return view('admin.payments.index');
    }
    public function create()
    {
        return view('admin.payments.create');
    }
    public function edit()
    {
        return view('admin.payments.edit');
    }
    // payment method
    public function paymentMethods()
    {
        return view('admin.payments.payment_methods');
    }
    public function createPaymentMethods()
    {
        return view('admin.payments.create_payment_methods');
    }
    public function editPaymentMethods()
    {
        return view('admin.payments.edit_payment_methods');
    }
}
