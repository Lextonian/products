<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SuccessResponse;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class ProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->get('per_page', 15);
        $products = Product::with('category')->paginate($perPage);
        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request): ProductResource
    {
        $product = Product::create($request->validated());
        return new ProductResource($product);
    }

    public function show(Product $product): ProductResource
    {
        return new ProductResource($product->load('category'));
    }

    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        $product->update($request->validated());
        return new ProductResource($product);
    }

    public function destroy(Product $product): SuccessResponse
    {
        $product->delete();
        return new SuccessResponse('Продукт удалён');
    }
}
