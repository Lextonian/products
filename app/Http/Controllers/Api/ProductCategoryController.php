<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SuccessResponse;
use App\Models\ProductCategory;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\ProductCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductCategoryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->get('per_page', 15);
        $cats = ProductCategory::paginate($perPage);
        return ProductCategoryResource::collection($cats);
    }

    public function store(StoreCategoryRequest $request): ProductCategoryResource
    {
        $cat = ProductCategory::create($request->validated());
        return new ProductCategoryResource($cat);
    }

    public function show(ProductCategory $productCategory): ProductCategoryResource
    {
        return new ProductCategoryResource($productCategory);
    }

    public function update(UpdateCategoryRequest $request, ProductCategory $productCategory): ProductCategoryResource
    {
        $productCategory->update($request->validated());
        return new ProductCategoryResource($productCategory);
    }

    public function destroy(ProductCategory $productCategory): SuccessResponse
    {
        $productCategory->delete();
        return new SuccessResponse('Категория удалена');
    }
}
