<?php

namespace App\Http\Controllers;

use App\Payment;

class PaymentsController extends Controller
{

    public function index()
    {
        $payments = Payment::query()->where('shop_id', auth()->id())->where('status', 'completed')->latest()->paginate(20);

        return view('payment.index', compact('payments'));
    }

}
