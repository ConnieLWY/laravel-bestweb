<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\CategoryResource;

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="Categories"
 * )
 */
class CategoryApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Get all categories",
     *     tags={"Categories"},
     *     @OA\Response(
     *         @OA\MediaType(mediaType="application/json"),
     *         response=200,
     *         description="Successful response"
     *     ),
     * security={{"sanctum": {}}}
     * )
     */
    public function index()
    {
        return CategoryResource::collection(Category::paginate(20));
    }

    /**
     * @OA\Post(
     *     path="/api/categories",
     *     summary="Create a new category",
     *     tags={"Categories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         @OA\MediaType(mediaType="application/json"),
     *         response=201,
     *         description="Category created successfully"
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $category = Category::create($request->only('name'));
        return new CategoryResource($category);
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     summary="Get a category by ID",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         @OA\MediaType(mediaType="application/json"),
     *         response=200,
     *         description="Category retrieved successfully"
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function show($id)
    {
        return new CategoryResource(Category::findOrFail($id));
    }

    /**
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     summary="Update a category by ID",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully"
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $category = Category::findOrFail($id);
        $category->update($request->only('name'));
        return new CategoryResource($category);
    }

    /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     summary="Delete a category by ID",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category deleted successfully"
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Category soft-deleted']);
    }

    /**
     * @OA\Post(
     *     path="/api/categories/bulk-delete",
     *     summary="Bulk delete categories",
     *     tags={"Categories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"ids"},
     *             @OA\Property(property="ids", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bulk delete successful"
     *     ),
     *     security={{"sanctum": {}}}
     * )
     */
    public function bulkDelete()
    {
        $ids = request('ids');
        Category::whereIn('id', $ids)->delete();
        return response()->json(['message' => 'Bulk delete successful']);
    }
}
