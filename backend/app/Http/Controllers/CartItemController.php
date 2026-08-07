<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CartItemController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        return CartItemResource::collection(
            $request->user()->cartItems()->with('product.category')->latest()->get()
        );
    }

    public function store(StoreCartItemRequest $request): CartItemResource|JsonResponse
    {
        $product = Product::where('is_active', true)->findOrFail($request->integer('product_id'));

        if ($request->integer('quantity') > $product->stock) {
            return response()->json(['message' => 'Requested quantity is not available.'], 422);
        }

        $cartItem = CartItem::updateOrCreate(
            ['user_id' => $request->user()->id, 'product_id' => $product->id],
            ['quantity' => $request->integer('quantity')]
        );

        return new CartItemResource($cartItem->load('product.category'));
    }

    public function show(Request $request, CartItem $cartItem): CartItemResource
    {
        abort_unless($cartItem->user_id === $request->user()->id, 403);

        return new CartItemResource($cartItem->load('product.category'));
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem): CartItemResource|JsonResponse
    {
        abort_unless($cartItem->user_id === $request->user()->id, 403);

        if ($request->integer('quantity') > $cartItem->product->stock) {
            return response()->json(['message' => 'Requested quantity is not available.'], 422);
        }

        $cartItem->update($request->validated());

        return new CartItemResource($cartItem->refresh()->load('product.category'));
    }

    public function destroy(Request $request, CartItem $cartItem): Response
    {
        abort_unless($cartItem->user_id === $request->user()->id, 403);

        $cartItem->delete();

        return response()->noContent();
    }
}
