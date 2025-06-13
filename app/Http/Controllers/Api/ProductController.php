<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Product::with('category');

        if (request()->has('category_id')) {
            $query->where('category_id', request()->category_id);
        }

        return ProductResource::collection($query->paginate(10));
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());
        return new ProductResource($product);
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->validated());
        return new ProductResource($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product soft-deleted']);
    }

    public function bulkDelete()
    {
        $ids = request('ids');
        Product::whereIn('id', $ids)->delete();
        return response()->json(['message' => 'Bulk delete successful']);
    }
}
