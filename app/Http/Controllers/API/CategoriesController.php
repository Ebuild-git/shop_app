<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{categories, sous_categories, proprietes};
use Illuminate\Support\Facades\Storage;

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

    /**
     * @OA\Get(
     *     path="/api/sub-categories",
     *     tags={"Categories"},
     *     summary="List all subcategories",
     *     description="Returns all subcategories with their parent category and full image URLs",
     *     @OA\Response(
     *         response=200,
     *         description="List of subcategories",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="titre", type="string", example="Caftan & Takchita"),
     *                     @OA\Property(property="id_categorie", type="integer", example=1),
     *                     @OA\Property(property="description", type="string", nullable=true, example=null),
     *                     @OA\Property(property="proprietes", type="array", @OA\Items(type="integer")),
     *                     @OA\Property(property="required", type="string"),
     *                     @OA\Property(property="order", type="integer", example=0),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time"),
     *                     @OA\Property(property="title_en", type="string", example="Caftan & Takchita"),
     *                     @OA\Property(property="title_ar", type="string", example="القفطان والتكشيطة"),
     *                     @OA\Property(
     *                         property="categorie",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="titre", type="string", example="Fashion & Vêtements"),
     *                         @OA\Property(property="description", type="string", example="Fashion & Vêtements looollmm"),
     *                         @OA\Property(property="pourcentage_gain", type="string", example="7.00"),
     *                         @OA\Property(property="luxury", type="boolean", example=false),
     *                         @OA\Property(property="order", type="integer", example=0),
     *                         @OA\Property(property="icon", type="string", example="http://127.0.0.1:8000/storage/uploads/categories/icon.png"),
     *                         @OA\Property(property="small_icon", type="string", example="http://127.0.0.1:8000/storage/uploads/categories/icon_small.png"),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time"),
     *                         @OA\Property(property="active", type="integer", example=1),
     *                         @OA\Property(property="title_en", type="string", example="Fashion & Clothing"),
     *                         @OA\Property(property="title_ar", type="string", example="الموضة والملابس")
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function list_sub_categorie()
    {
        $subcategories = sous_categories::with('categorie')
            ->orderBy('order')
            ->get()
            ->map(function ($sub) {
                if ($sub->categorie) {
                    if ($sub->categorie->icon && !filter_var($sub->categorie->icon, FILTER_VALIDATE_URL)) {
                        $sub->categorie->icon = asset('storage/' . $sub->categorie->icon);
                    }

                    if ($sub->categorie->small_icon && !filter_var($sub->categorie->small_icon, FILTER_VALIDATE_URL)) {
                        $sub->categorie->small_icon = asset('storage/' . $sub->categorie->small_icon);
                    }
                }

                return $sub;
            });

        return response()->json([
            'success' => true,
            'data' => $subcategories
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/sub-categories/{id}",
     *     tags={"Categories"},
     *     summary="Get subcategory details",
     *     description="Returns a single subcategory with its parent category and full image URLs",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the subcategory",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Subcategory details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="titre", type="string", example="Caftan & Takchita"),
     *                 @OA\Property(property="id_categorie", type="integer", example=1),
     *                 @OA\Property(property="description", type="string", nullable=true),
     *                 @OA\Property(property="proprietes", type="array", @OA\Items(type="integer")),
     *                 @OA\Property(property="required", type="string"),
     *                 @OA\Property(property="order", type="integer", example=0),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(property="title_en", type="string", example="Caftan & Takchita"),
     *                 @OA\Property(property="title_ar", type="string", example="القفطان والتكشيطة"),
     *                 @OA\Property(
     *                     property="categorie",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="titre", type="string", example="Fashion & Vêtements"),
     *                     @OA\Property(property="description", type="string", example="Fashion & Vêtements looollmm"),
     *                     @OA\Property(property="pourcentage_gain", type="string", example="7.00"),
     *                     @OA\Property(property="luxury", type="boolean", example=false),
     *                     @OA\Property(property="order", type="integer", example=0),
     *                     @OA\Property(property="icon", type="string", example="http://127.0.0.1:8000/storage/uploads/categories/icon.png"),
     *                     @OA\Property(property="small_icon", type="string", example="http://127.0.0.1:8000/storage/uploads/categories/icon_small.png"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time"),
     *                     @OA\Property(property="active", type="integer", example=1),
     *                     @OA\Property(property="title_en", type="string", example="Fashion & Clothing"),
     *                     @OA\Property(property="title_ar", type="string", example="الموضة والملابس")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subcategory not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Categorie not found")
     *         )
     *     )
     * )
     */
    public function details_sub_categorie($id)
    {
        try {
            $sub_category = sous_categories::with('categorie')->findOrFail($id);

            if ($sub_category->categorie) {

                if ($sub_category->categorie->icon && !filter_var($sub_category->categorie->icon, FILTER_VALIDATE_URL)) {
                    $sub_category->categorie->icon = asset('storage/' . $sub_category->categorie->icon);
                }

                if ($sub_category->categorie->small_icon && !filter_var($sub_category->categorie->small_icon, FILTER_VALIDATE_URL)) {
                    $sub_category->categorie->small_icon = asset('storage/' . $sub_category->categorie->small_icon);
                }
            }

            return response()->json([
                'success' => true,
                'data' => $sub_category
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Categorie not found"
            ], 404);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/proprietes",
     *     tags={"Categories"},
     *     summary="List all properties",
     *     description="Returns all properties with their details including name, type, display format, options, and configuration flags. Properties are ordered by their display order.",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation - List of all properties",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="Array of property objects",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=13, description="Unique identifier for the property"),
     *                     @OA\Property(property="nom", type="string", example="Taille Bébé", description="Name of the property"),
     *                     @OA\Property(property="type", type="string", example="option", description="Type of property: 'text', 'number', 'option', 'color'"),
     *                     @OA\Property(property="affichage", type="string", example="case", description="Display format for the property"),
     *                     @OA\Property(
     *                         property="options",
     *                         type="array",
     *                         description="Available options for property types that support predefined values",
     *                         nullable=true,
     *                         @OA\Items(type="string", example="Prématurés")
     *                     ),
     *                     @OA\Property(property="required", type="integer", example=0, description="Whether the property is required (1) or optional (0)"),
     *                     @OA\Property(property="order", type="integer", example=0, description="Display order for sorting properties"),
     *                     @OA\Property(property="show_in_filter", type="integer", example=1, description="Whether to show this property in filters (1) or not (0)"),
     *                     @OA\Property(property="show_in_front", type="integer", example=1, description="Whether to show this property in frontend (1) or not (0)"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */
    public function list_proprietes()
    {
        $proprietes = proprietes::orderBy('order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $proprietes
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/proprietes/{id}",
     *     tags={"Categories"},
     *     summary="Get property details",
     *     description="Returns detailed information about a specific property by its ID. Includes all property attributes such as name, type, options, and configuration flags.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the property to retrieve",
     *         @OA\Schema(type="integer", example=13)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation - Property details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="Property object with all details",
     *                 @OA\Property(property="id", type="integer", example=13, description="Unique identifier for the property"),
     *                 @OA\Property(property="nom", type="string", example="Taille Bébé", description="Name of the property"),
     *                 @OA\Property(property="type", type="string", example="option", description="Type of property: 'text', 'number', 'option', 'color'"),
     *                 @OA\Property(property="affichage", type="string", example="case", description="Display format for the property"),
     *                 @OA\Property(
     *                     property="options",
     *                     type="array",
     *                     description="Available options for property types that support predefined values",
     *                     nullable=true,
     *                     @OA\Items(type="string", example="Prématurés")
     *                 ),
     *                 @OA\Property(property="required", type="integer", example=0, description="Whether the property is required (1) or optional (0)"),
     *                 @OA\Property(property="order", type="integer", example=0, description="Display order for sorting properties"),
     *                 @OA\Property(property="show_in_filter", type="integer", example=1, description="Whether to show this property in filters (1) or not (0)"),
     *                 @OA\Property(property="show_in_front", type="integer", example=1, description="Whether to show this property in frontend (1) or not (0)"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Property not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="propriete not found")
     *         )
     *     )
     * )
     */
    public function details_proprietes($id)
    {
        try {
            $proprietes = proprietes::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $proprietes
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "propriete not found"
            ], 404);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/banners",
     *     summary="Get active categories for banners",
     *     description="Returns a list of active categories ordered by their 'order' field. Each category includes its icon full path, titre, and luxury status.",
     *     tags={"Banners"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(
     *                 property="categories",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=12),
     *                     @OA\Property(property="titre", type="string", example="High Fashion"),
     *                     @OA\Property(property="luxury", type="boolean", example=true),
     *                     @OA\Property(
     *                         property="icon",
     *                         type="string",
     *                         example="https://yourdomain.com/storage/icons/icon.png"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function banners(){
       $categories = categories::where('active', true)->orderBy('order')
        ->get()
        ->map(function ($category) {
            return [
                'id'             => $category->id,
                'titre'          => $category->titre,
                'luxury'         => $category->luxury,
                'icon' => $category->icon ? asset('storage/' . $category->icon) : null
            ];
        });

        return response()->json([
            'status'     => true,
            'categories' => $categories,
        ]);
    }
}

