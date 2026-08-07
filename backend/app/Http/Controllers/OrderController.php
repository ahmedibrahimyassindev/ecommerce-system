<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $orders = $request->user()->orders()
            ->with('items', 'payment')
            ->latest()
            ->paginate(request('per_page', 15));

        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request): OrderResource|JsonResponse
    {
        $cartItems = $request->user()->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty.'], 422);
        }

        foreach ($cartItems as $cartItem) {
            if (! $cartItem->product->is_active || $cartItem->quantity > $cartItem->product->stock) {
                return response()->json([
                    'message' => "Product {$cartItem->product->name} is not available in the requested quantity.",
                ], 422);
            }
        }

        $order = DB::transaction(function () use ($request, $cartItems) {
            $subtotal = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);
            $shippingTotal = 0;
            $paymentMethod = (string) $request->string('payment_method');

            $order = Order::create([
                'user_id' => $request->user()->id,
                'order_number' => 'ORD-'.now()->format('Ymd').'-'.Str::upper(Str::random(8)),
                'subtotal' => $subtotal,
                'shipping_total' => $shippingTotal,
                'total' => $subtotal + $shippingTotal,
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
                'shipping_name' => (string) $request->string('shipping_name'),
                'shipping_phone' => (string) $request->string('shipping_phone'),
                'shipping_address' => (string) $request->string('shipping_address'),
                'shipping_city' => (string) $request->string('shipping_city'),
                'shipping_country' => $request->input('shipping_country', 'Egypt'),
                'notes' => $request->input('notes'),
            ]);

            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                $lineTotal = $product->price * $cartItem->quantity;

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $product->price,
                    'quantity' => $cartItem->quantity,
                    'total' => $lineTotal,
                ]);

                $product->decrement('stock', $cartItem->quantity);
            }

            $order->payment()->create([
                'method' => $order->payment_method,
                'status' => $order->payment_status,
                'amount' => $order->total,
            ]);

            $request->user()->cartItems()->delete();

            return $order;
        });

        return new OrderResource($order->load('items', 'payment'));
    }

    public function show(Request $request, Order $order): OrderResource
    {
        abort_unless($order->user_id === $request->user()->id || $request->user()->is_admin, 403);

        return new OrderResource($order->load('items', 'payment'));
    }

    public function update(UpdateOrderStatusRequest $request, Order $order): OrderResource
    {
        abort_unless($request->user()->is_admin, 403);

        $order->update($request->validated());

        if ($request->has('payment_status') && $order->payment) {
            $order->payment->update(['status' => (string) $request->string('payment_status')]);
        }

        return new OrderResource($order->refresh()->load('items', 'payment'));
    }

    public function destroy(Request $request, Order $order): JsonResponse
    {
        abort_unless($order->user_id === $request->user()->id || $request->user()->is_admin, 403);
        abort_if(in_array($order->status, ['shipped', 'delivered'], true), 422, 'This order can no longer be cancelled.');

        $order->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Order cancelled successfully.']);
    }
}
