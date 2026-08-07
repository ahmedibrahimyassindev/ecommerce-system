<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PaymentController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        abort_unless($request->user()->is_admin, 403);

        return PaymentResource::collection(Payment::with('order')->latest()->paginate(request('per_page', 15)));
    }

    public function show(Request $request, Payment $payment): PaymentResource
    {
        $payment->load('order');

        abort_unless($request->user()->is_admin || $payment->order->user_id === $request->user()->id, 403);

        return new PaymentResource($payment);
    }
}
