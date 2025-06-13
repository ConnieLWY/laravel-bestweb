<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;

/**
 * @OA\Tag(
 *     name="Products",
 *     description="Operations about products"
 * )
 */
class ProductApiController extends Controller
{
 /**
 * @OA\Get(
 *     path="/api/products",
 *     summary="Get paginated list of products",
 *     tags={"Products"},
 *     @OA\Parameter(
 *         name="enabled",
 *         in="query",
 *         description="Filter by enabled status",
 *         required=false,
 *         @OA\Schema(type="boolean")
 *     ),
 *      @OA\Parameter(
 *         name="category_id",
 *         in="query",
 *         description="Filter by category ID",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="name",
 *         in="query",
 *         description="Filter by product name",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="paginate",
 *         in="query",
 *         description="Number of products per page",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Paginated products list",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer"),
 *                     @OA\Property(property="name", type="string"),
 *                     @OA\Property(property="category_id", type="integer"),
 *                     @OA\Property(property="category_name", type="string"),
 *                     @OA\Property(property="description", type="string"),
 *                     @OA\Property(property="price", type="string"),
 *                     @OA\Property(property="stock", type="integer"),
 *                     @OA\Property(property="enabled", type="boolean"),
 *                     @OA\Property(property="created_at", type="string", format="date-time")
 *                 )
 *             ),
 *             @OA\Property(
 *                 property="links",
 *                 type="object",
 *                 @OA\Property(property="first", type="string"),
 *                 @OA\Property(property="last", type="string"),
 *                 @OA\Property(property="prev", type="string", nullable=true),
 *                 @OA\Property(property="next", type="string", nullable=true)
 *             ),
 *             @OA\Property(
 *                 property="meta",
 *                 type="object",
 *                 @OA\Property(property="current_page", type="integer"),
 *                 @OA\Property(property="from", type="integer"),
 *                 @OA\Property(property="last_page", type="integer"),
 *                 @OA\Property(property="path", type="string"),
 *                 @OA\Property(property="per_page", type="integer"),
 *                 @OA\Property(property="to", type="integer"),
 *                 @OA\Property(property="total", type="integer")
 *             )
 *         )
 *     ),
 *     security={{"sanctum": {}}}
 * )
 */


    public function index()
    {
        $query = Product::with('category');

        if (request()->has('enabled')) {
            $enabled = filter_var(request()->enabled, FILTER_VALIDATE_BOOLEAN);
            $query->where('enabled', $enabled);
        }

        if (request()->has('category_id')) {
            $query->where('category_id', request()->category_id);
        }

        if (request()->has('name')) {
            $query->where('name', 'LIKE', '%' . request()->name . '%');
        }

        return ProductResource::collection($query->paginate(10));
    }
    /**
     * @OA\Post(
     *     path="/api/products",
     *     summary="Create a new product",
     *     tags={"Products"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","category_id","price","stock","enabled"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="category_id", type="integer"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="number", format="float"),
     *             @OA\Property(property="stock", type="integer"),
     *             @OA\Property(property="enabled", type="boolean")
     *         )
     *     ),
     *     @OA\Response(
     *         @OA\MediaType(mediaType="application/json"),
     *         response=201,
     *         description="Product created successfully"
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());
        return new ProductResource($product);
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     summary="Get a product by ID",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="category_id", type="integer"),
     *             @OA\Property(property="category_name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="number", format="float"),
     *             @OA\Property(property="stock", type="integer"),
     *             @OA\Property(property="enabled", type="boolean"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return new ProductResource($product);
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     summary="Update a product by ID",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","category_id","price","stock","enabled"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="category_id", type="integer"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="number", format="float"),
     *             @OA\Property(property="stock", type="integer"),
     *             @OA\Property(property="enabled", type="boolean")
     *         )
     *     ),
     *     @OA\Response(
     *         @OA\MediaType(mediaType="application/json"),
     *         response=200,
     *         description="Product updated successfully"
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->validated());
        return new ProductResource($product);
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     summary="Delete a product by ID",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         @OA\MediaType(mediaType="application/json"),
     *         response=200,
     *         description="Product soft-deleted"
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product soft-deleted']);
    }

    /**
     * @OA\Post(
     *     path="/api/products/bulk-delete",
     *     summary="Bulk delete products",
     *     tags={"Products"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"ids"},
     *             @OA\Property(property="ids", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(
     *         @OA\MediaType(mediaType="application/json"),
     *         response=200,
     *         description="Bulk delete successful"
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */

    public function bulkDelete()
    {
        $ids = request('ids');
        Product::whereIn('id', $ids)->delete();
        return response()->json(['message' => 'Bulk delete successful']);
    }

    /**
     * @OA\Get(
     *     path="/api/export/products",
     *     summary="Export products to Excel",
     *     tags={"Products"},
     *     @OA\Response(
     *         @OA\MediaType(mediaType="application/json"),
     *         response=200,
     *         description="Products exported successfully"
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */

    public function export()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }
}
