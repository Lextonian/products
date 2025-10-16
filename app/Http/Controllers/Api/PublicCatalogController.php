<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Http\Resources\ProductResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;

class PublicCatalogController extends Controller
{
    /**
     * GET /api/public/products
     *
     * Возвращает список продуктов с пагинацией
     * @param  \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\JsonResponse
     */
    public function products(Request $request): AnonymousResourceCollection
    {
        $perPage = 6;

        $products = Product::with('category')->paginate($perPage);
        return ProductResource::collection($products);
    }

    /**
     * GET /api/public/products/{product:slug}
     *
     * Возвращает ресурс продукта
     * @param  \App\Models\Product  $product
     * @return  \App\Http\Resources\ProductResource
     */
    public function product(Product $product): ProductResource
    {
        // Возвращает ресурс продукта
        return new ProductResource($product->load('category'));
    }

    /**
     * GET /api/public/product_categories
     *
     * Вывод дерева категорий
     * @param  \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\JsonResponse
     */
    public function categoriesTree(Request $request): JsonResponse
    {
        // Пагинация только корневых категорий (по 6 штук)
        $perPage = 6;
        $roots = ProductCategory::whereNull('parent_id')
            ->orderBy('title')
            ->paginate($perPage);

        // Строим дерево
        $tree = $this->buildTree($roots->getCollection());

        // Возвращаем структуру + meta/links для пагинации
        return response()->json([
            'data' => $tree,
            'meta' => [
                'current_page' => $roots->currentPage(),
                'last_page' => $roots->lastPage(),
                'per_page' => $roots->perPage(),
                'total' => $roots->total(),
            ],
            'links' => [
                'first' => $roots->url(1),
                'last' => $roots->url($roots->lastPage()),
                'next' => $roots->nextPageUrl(),
                'prev' => $roots->previousPageUrl(),
            ]
        ]);
    }
    /**
     * Build tree from the given collection of categories
     * @param  \Illuminate\Support\Collection $categories
     * @return  array
     */

    /**
     * GET /api/public/product_categories_with_products
     *
     * Дерево категорий, в каждой products => paginated(6)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\JsonResponse
     */
    public function categoriesWithProducts(Request $request): JsonResponse
    {
        $perPage = 6; // пагинация категорий
        $productsPerCategory = 6; // пагинация продуктов внутри категории

        // пагинация категорий
        $categories = ProductCategory::whereNull('parent_id')
            ->orderBy('title')
            ->paginate($perPage);

        $data = $categories->map(function ($category) use ($productsPerCategory) {
            // продукты внутри категории, paginated(6)
            $products = $category->products()
                ->orderBy('title')
                ->paginate($productsPerCategory);

            return [
                'id' => $category->id,
                'title' => $category->title,
                // продукты
                'products' => $products->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'title' => $p->title,
                        'slug' => $p->slug,
                        'description' => $p->description,
                    ];
                }),
            ];
        });

        return response()->json([
            'data' => $data,
            'meta' => [
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
            ],
        ]);
    }

    /**
     * Рекурсивно строит дерево из коллекции узлов.
     *
     * Каждый узел содержит ключи 'id', 'title' и 'childs'.
     *
     * 'childs' равен null, если у узла нет дочерних элементов.
     *
     * @param  \Illuminate\Support\Collection  $nodes
     * @return  \Illuminate\Support\Collection
     */
    protected function buildTree($nodes): Collection
    {
        return $nodes->map(function ($node) {
            $children = $node->children()->orderBy('title')->get();
            return [
                'id' => $node->id,
                'title' => $node->title,
                'childs' => $children->isEmpty() ? null : $this->buildTree($children),
            ];
        })->values();
    }
}
