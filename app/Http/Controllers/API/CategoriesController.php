<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\categories;

class CategoriesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     operationId="listCategories",
     *     tags={"Categories"},
     *     summary="Get all active categories",
     *     description="Returns a list of all active categories with full icon URLs.",
     *     @OA\Response(
     *         response=200,
     *         description="List of categories",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="titre", type="string", example="Fashion & Vêtements"),
     *                     @OA\Property(property="description", type="string", example="Fashion & Vêtements looollmm"),
     *                     @OA\Property(property="pourcentage_gain", type="string", example="7.00"),
     *                     @OA\Property(property="luxury", type="boolean", example=false),
     *                     @OA\Property(property="order", type="integer", example=0),
     *                     @OA\Property(property="icon", type="string", example="http://127.0.0.1:8000/storage/uploads/categories/goCedMgqRvvQQ7xzuwNBgraFZSuuvWShzRCknYvP.png"),
     *                     @OA\Property(property="small_icon", type="string", example="http://127.0.0.1:8000/storage/uploads/categories/oE8n20szUVwCTh4pSCy8VXSP0KvTliyMBgX2kDxg.png"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-24T11:24:48.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-14T17:41:17.000000Z"),
     *                     @OA\Property(property="active", type="integer", example=1),
     *                     @OA\Property(property="title_en", type="string", example="Fashion & Clothing"),
     *                     @OA\Property(property="title_ar", type="string", example="الموضة والملابس")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function list_categorie()
    {
        $categories = categories::where('active', true)
            ->orderBy('order')
            ->get()
            ->map(function ($cat) {
                $cat->icon = $cat->icon ? asset('storage/' . $cat->icon) : null;
                $cat->small_icon = $cat->small_icon ? asset('storage/' . $cat->small_icon) : null;
                return $cat;
            });

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     operationId="getCategoryById",
     *     tags={"Categories"},
     *     summary="Get a single category by ID",
     *     description="Returns the details of a single active category with full icon URLs.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the category",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category details",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="titre", type="string", example="Fashion & Vêtements"),
     *                 @OA\Property(property="description", type="string", example="Fashion & Vêtements looollmm"),
     *                 @OA\Property(property="pourcentage_gain", type="string", example="7.00"),
     *                 @OA\Property(property="luxury", type="boolean", example=false),
     *                 @OA\Property(property="order", type="integer", example=0),
     *                 @OA\Property(property="icon", type="string", example="http://127.0.0.1:8000/storage/uploads/categories/goCedMgqRvvQQ7xzuwNBgraFZSuuvWShzRCknYvP.png"),
     *                 @OA\Property(property="small_icon", type="string", example="http://127.0.0.1:8000/storage/uploads/categories/oE8n20szUVwCTh4pSCy8VXSP0KvTliyMBgX2kDxg.png"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-24T11:24:48.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-14T17:41:17.000000Z"),
     *                 @OA\Property(property="active", type="integer", example=1),
     *                 @OA\Property(property="title_en", type="string", example="Fashion & Clothing"),
     *                 @OA\Property(property="title_ar", type="string", example="الموضة والملابس")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Categorie not found")
     *         )
     *     )
     * )
     */
    public function details_categorie($id)
    {
        try {
            $category = categories::where('active', true)->findOrFail($id);

            $category->icon = $category->icon ? asset('storage/' . $category->icon) : null;
            $category->small_icon = $category->small_icon ? asset('storage/' . $category->small_icon) : null;

            return response()->json([
                'success' => true,
                'data' => $category
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Categorie not found"
            ], 404);
        }
    }

}
