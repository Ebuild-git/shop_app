<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\regions;

class ShopController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/regions",
     *     operationId="getRegions",
     *     tags={"Location"},
     *     summary="Get all regions",
     *     description="Returns a list of all regions with their IDs and names.",
     *     @OA\Response(
     *         response=200,
     *         description="List of regions",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nom", type="string", example="Béni Mellal-Khénifra"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-03T09:58:34.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-03T09:58:34.000000Z")
     *             )
     *         )
     *     )
     * )
     */
    public function regions()
    {
        $regions = regions::all();
        return response()->json($regions);
    }

}
