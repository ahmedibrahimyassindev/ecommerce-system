<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $categories = Category::query()
            ->withCount('products')
            ->latest()
            ->paginate(request('per_page', 15));

        return CategoryResource::collection($categories);
    }

    public function store(StoreCategoryRequest $request): CategoryResource
    {
        abort_unless($request->user()->is_admin, 403);

        $data = $request->validated();
        $data['slug'] ??= Str::slug($data['name']);

        return new CategoryResource(Category::create($data));
    }

    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category->loadCount('products'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): CategoryResource
    {
        abort_unless($request->user()->is_admin, 403);

        $data = $request->validated();
        $data['slug'] ??= isset($data['name']) ? Str::slug($data['name']) : $category->slug;
        $category->update($data);

        return new CategoryResource($category->refresh()->loadCount('products'));
    }

    public function destroy(\Illuminate\Http\Request $request, Category $category): Response
    {
        abort_unless($request->user()->is_admin, 403);

        $category->delete();

        return response()->noContent();
    }
}
