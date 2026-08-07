<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $products = Product::query()
            ->with('category')
            ->when(request('category_id'), fn ($query, $categoryId) => $query->where('category_id', $categoryId))
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when(request()->has('is_active'), fn ($query) => $query->where('is_active', request()->boolean('is_active')))
            ->latest()
            ->paginate(request('per_page', 15));

        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request): ProductResource
    {
        abort_unless($request->user()->is_admin, 403);

        $data = $request->validated();
        $data['slug'] ??= Str::slug($data['name']);

        return new ProductResource(Product::create($data)->load('category'));
    }

    public function show(Product $product): ProductResource
    {
        return new ProductResource($product->load('category'));
    }

    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        abort_unless($request->user()->is_admin, 403);

        $data = $request->validated();
        $data['slug'] ??= isset($data['name']) ? Str::slug($data['name']) : $product->slug;
        $product->update($data);

        return new ProductResource($product->refresh()->load('category'));
    }

    public function destroy(\Illuminate\Http\Request $request, Product $product): Response
    {
        abort_unless($request->user()->is_admin, 403);

        $product->delete();

        return response()->noContent();
    }
}
