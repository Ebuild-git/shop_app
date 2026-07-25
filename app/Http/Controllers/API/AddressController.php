<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\regions;
use App\Models\User;
use App\Models\City;

class AddressController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/addresses",
     *     tags={"Addresses"},
     *     summary="Fetch the user's address",
     *     description="Returns the address stored directly on the authenticated user.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Address retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="address", type="object",
     *                 @OA\Property(property="region", type="integer"),
     *                 @OA\Property(property="city_id", type="integer", nullable=true),
     *                 @OA\Property(property="city", type="string", nullable=true),
     *                 @OA\Property(property="rue", type="string"),
     *                 @OA\Property(property="nom_batiment", type="string"),
     *                 @OA\Property(property="etage", type="string", nullable=true),
     *                 @OA\Property(property="num_appartement", type="string", nullable=true),
     *                 @OA\Property(property="phone_number", type="string", nullable=true)
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $address = [
            'region'          => $user->region,
            'city_id'         => $user->city_id,
            'city'            => $user->city ? $user->city->name : null,
            'rue'             => $user->rue,
            'nom_batiment'    => $user->nom_batiment,
            'etage'           => $user->etage,
            'num_appartement' => $user->num_appartement,
            'phone_number'    => $user->phone_number,
        ];

        return response()->json([
            'success' => true,
            'address' => $address,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/addresses/main",
     *     tags={"Addresses"},
     *     summary="Update the user's address",
     *     description="Update the address fields directly on the authenticated user.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="region", type="integer"),
     *             @OA\Property(property="city_id", type="integer", nullable=true),
     *             @OA\Property(property="rue", type="string"),
     *             @OA\Property(property="nom_batiment", type="string"),
     *             @OA\Property(property="etage", type="string", nullable=true),
     *             @OA\Property(property="num_appartement", type="string", nullable=true),
     *             @OA\Property(property="phone_number", type="string", nullable=true, description="Exactly 8 digits")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Address updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function updateMainAddress(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'region' => 'required|exists:regions,id',
            'city_id' => 'nullable|exists:cities,id',
            'rue' => 'required|string|max:255',
            'nom_batiment' => 'required|string|max:255',
            'etage' => 'nullable|string|max:50',
            'num_appartement' => 'nullable|string|max:50',
            'phone_number' => 'nullable|string|max:10',
        ], [
            'phone_number.max' => 'The phone number must not exceed 10 digits.',
        ]);

        $user->update([
            'region'          => $validated['region'],
            'city_id'         => $validated['city_id'] ?? null,
            'rue'             => $validated['rue'],
            'nom_batiment'    => $validated['nom_batiment'],
            'etage'           => $validated['etage'] ?? null,
            'num_appartement' => $validated['num_appartement'] ?? null,
            'phone_number'    => $validated['phone_number'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Address updated successfully',
            'data' => [
                'region'          => $user->region,
                'city_id'         => $user->city_id,
                'rue'             => $user->rue,
                'nom_batiment'    => $user->nom_batiment,
                'etage'           => $user->etage,
                'num_appartement' => $user->num_appartement,
                'phone_number'    => $user->phone_number,
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/addresses/completeness",
     *     tags={"Addresses"},
     *     summary="Check if the authenticated user's address is complete",
     *     description="Checks the address fields stored directly on the user.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Address completeness status",
     *         @OA\JsonContent(
     *             @OA\Property(property="success",             type="boolean"),
     *             @OA\Property(property="is_complete",         type="boolean"),
     *             @OA\Property(property="missing_fields",      type="array", @OA\Items(type="string")),
     *             @OA\Property(property="recommended_fields",  type="array", @OA\Items(type="string")),
     *             @OA\Property(property="address",             type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function checkCompleteness(Request $request)
    {
        $user = $request->user();

        $address = [
            'region'          => $user->region,
            'city_id'         => $user->city_id,
            'rue'             => $user->rue,
            'nom_batiment'    => $user->nom_batiment,
            'etage'           => $user->etage,
            'num_appartement' => $user->num_appartement,
            'phone_number'    => $user->phone_number,
        ];

        // Display RDC when floor is 0
        $etageDisplay = ($address['etage'] !== null && $address['etage'] !== '')
            ? ($address['etage'] == 0 ? 'RDC (Rez-de-chaussée)' : $address['etage'])
            : null;

        $requiredFields = [
            'region'       => 'Region',
            'rue'          => 'Street (Rue)',
            'nom_batiment' => 'Building name',
            'phone_number' => 'Phone number',
        ];

        $recommendedFields = [
            'city_id'         => 'City',
            'etage'           => 'Floor (Étage)',
            'num_appartement' => 'Apartment number',
        ];

        $missingRequired    = [];
        $missingRecommended = [];

        $isMissing = fn($value) => $value === null || $value === '';

        foreach ($requiredFields as $field => $label) {
            if ($isMissing($address[$field])) {
                $missingRequired[] = $label;
            }
        }

        foreach ($recommendedFields as $field => $label) {
            if ($isMissing($address[$field])) {
                $missingRecommended[] = $label;
            }
        }

        return response()->json([
            'success'            => true,
            'is_complete'        => empty($missingRequired),
            'missing_fields'     => $missingRequired,
            'recommended_fields' => $missingRecommended,
            'address'            => array_merge($address, [
                'etage_display' => $etageDisplay,
            ]),
        ]);
    }
}
