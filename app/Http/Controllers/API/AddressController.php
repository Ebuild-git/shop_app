<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAddress;
use App\Models\regions;
use App\Models\User;
use App\Models\City;

class AddressController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/addresses",
     *     tags={"Addresses"},
     *     summary="Fetch the active address and secondary addresses",
     *     description="Returns the main address and secondary addresses. If a secondary address is set as default, it becomes the active address.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Active and secondary addresses retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="active_address", type="object",
     *                 @OA\Property(property="id", type="integer", nullable=true),
     *                 @OA\Property(property="region", type="integer"),
     *                 @OA\Property(property="address", type="string"),
     *                 @OA\Property(property="rue", type="string"),
     *                 @OA\Property(property="nom_batiment", type="string"),
     *                 @OA\Property(property="etage", type="string", nullable=true),
     *                 @OA\Property(property="num_appartement", type="string", nullable=true),
     *                 @OA\Property(property="phone_number", type="string", nullable=true),
     *                 @OA\Property(property="is_default", type="boolean")
     *             ),
     *             @OA\Property(property="secondary_addresses", type="array",
     *                 @OA\Items(type="object")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $mainAddress = [
            'id'              => null,
            'region'          => $user->region,
            'city_id'         => $user->city_id,
            'city'           => $user->city ? $user->city->name : null,
            'rue'             => $user->rue,
            'nom_batiment'    => $user->nom_batiment,
            'etage'           => $user->etage,
            'num_appartement' => $user->num_appartement,
            'phone_number'    => $user->phone_number,
            'is_default'      => false,
        ];

        $mapSecondary = function ($address) {
            return [
                'id'              => $address->id,
                'region'          => $address->region,
                'city_id'         => $address->city_id,
                'city'           => $address->city ? $address->city->name : null,
                'rue'             => $address->street,
                'nom_batiment'    => $address->building_name,
                'etage'           => $address->floor,
                'num_appartement' => $address->apartment_number,
                'phone_number'    => $address->phone_number,
                'is_default'      => $address->is_default,
            ];
        };

        $secondaryAddresses = UserAddress::where('user_id', $user->id)->get();
        $secondaryDefault   = $secondaryAddresses->firstWhere('is_default', true);

        if ($secondaryDefault) {
            $activeAddress      = $mapSecondary($secondaryDefault);
            $secondaryAddresses = $secondaryAddresses
                ->where('is_default', false)
                ->map($mapSecondary)
                ->values();
            $secondaryAddresses->push($mainAddress);
        } else {
            $mainAddress['is_default'] = true;
            $activeAddress             = $mainAddress;
            $secondaryAddresses        = $secondaryAddresses->map($mapSecondary)->values();
        }

        return response()->json([
            'success'             => true,
            'active_address'      => $activeAddress,
            'secondary_addresses' => $secondaryAddresses,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/addresses/main",
     *     tags={"Addresses"},
     *     summary="Update the main address in users",
     *     description="Update the main address fields for the authenticated user",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="region", type="integer"),
     *             @OA\Property(property="city_id", type="integer", nullable=true),
     *             @OA\Property(property="address", type="string"),
     *             @OA\Property(property="rue", type="string"),
     *             @OA\Property(property="nom_batiment", type="string"),
     *             @OA\Property(property="etage", type="string", nullable=true),
     *             @OA\Property(property="num_appartement", type="string", nullable=true),
     *             @OA\Property(property="phone_number", type="string", nullable=true, description="Exactly 8 digits")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Main address updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="region", type="integer"),
     *                 @OA\Property(property="address", type="string"),
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
        ],[
            'phone_number.max' => 'The phone number must not exceed 10 digits.',
        ]);

        $user->update([
            'region' => $validated['region'],
            'city_id' => $validated['city_id'] ?? null,
            'rue' => $validated['rue'],
            'nom_batiment' => $validated['nom_batiment'],
            'etage' => $validated['etage'] ?? null,
            'num_appartement' => $validated['num_appartement'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Main address updated successfully',
            'data' => [
                'region' => $user->region,
                'city_id' => $user->city_id,
                'rue' => $user->rue,
                'nom_batiment' => $user->nom_batiment,
                'etage' => $user->etage,
                'num_appartement' => $user->num_appartement,
                'phone_number' => $user->phone_number,
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/addresses",
     *     tags={"Addresses"},
     *     summary="Add a new secondary address",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="region", type="integer"),
     *             @OA\Property(property="city", type="string"),
     *            @OA\Property(property="city_id", type="integer", nullable=true),
     *             @OA\Property(property="street", type="string", nullable=true),
     *             @OA\Property(property="building_name", type="string", nullable=true),
     *             @OA\Property(property="floor", type="string", nullable=true),
     *             @OA\Property(property="apartment_number", type="string", nullable=true),
     *             @OA\Property(property="phone_number", type="string", nullable=true),
     *             @OA\Property(property="is_default", type="boolean", nullable=true, description="Set as default secondary address")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Secondary address added successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'region' => 'required|exists:regions,id',
            'city_id' => 'nullable|exists:cities,id',
            'street' => 'nullable|string|max:255',
            'building_name' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:50',
            'apartment_number' => 'nullable|string|max:50',
            'phone_number' => 'nullable|string|max:10',
            'is_default' => 'nullable|boolean',
        ]);

        if (!isset($validated['is_default'])) {
            $validated['is_default'] = false;
        }

        if ($validated['is_default']) {
            UserAddress::where('user_id', $user->id)->update(['is_default' => false]);
        }

        $address = UserAddress::create(array_merge($validated, [
            'user_id' => $user->id,
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Secondary address added successfully',
            'data' => $address
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/addresses/{id}",
     *     tags={"Addresses"},
     *     summary="Update a secondary address",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the secondary address",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="region", type="integer"),
     *             @OA\Property(property="city", type="string"),
     *            @OA\Property(property="city_id", type="integer", nullable=true),
     *             @OA\Property(property="street", type="string", nullable=true),
     *             @OA\Property(property="building_name", type="string", nullable=true),
     *             @OA\Property(property="floor", type="string", nullable=true),
     *             @OA\Property(property="apartment_number", type="string", nullable=true),
     *             @OA\Property(property="phone_number", type="string", nullable=true),
     *             @OA\Property(property="is_default", type="boolean", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Secondary address updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();

        $address = UserAddress::where('user_id', $user->id)->findOrFail($id);

        $validated = $request->validate([
            'region' => 'sometimes|exists:regions,id',
            'city_id' => 'nullable|exists:cities,id',
            'street' => 'nullable|string|max:255',
            'building_name' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:50',
            'apartment_number' => 'nullable|string|max:50',
            'phone_number' => 'nullable|string|max:10',
            'is_default' => 'nullable|boolean',
        ]);

        if (isset($validated['is_default']) && $validated['is_default']) {
            UserAddress::where('user_id', $user->id)->update(['is_default' => false]);
        }

        $address->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Secondary address updated successfully',
            'data' => $address
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/addresses/{id}",
     *     tags={"Addresses"},
     *     summary="Delete a secondary address",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the secondary address to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Secondary address deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $address = UserAddress::where('user_id', $user->id)->findOrFail($id);

        $address->delete();

        return response()->json([
            'success' => true,
            'message' => 'Secondary address deleted successfully'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/addresses/{id}/set-default",
     *     tags={"Addresses"},
     *     summary="Set a secondary address as default",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the secondary address to set as default",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Secondary address set as default successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function setDefault(Request $request, $id)
    {
        $user = $request->user();

        UserAddress::where('user_id', $user->id)->update(['is_default' => false]);

        $address = UserAddress::where('user_id', $user->id)->findOrFail($id);
        $address->is_default = true;
        $address->save();

        return response()->json([
            'success' => true,
            'message' => 'Secondary address set as default',
            'data' => $address
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/addresses/{id}/unset-default",
     *     tags={"Addresses"},
     *     summary="Unset a secondary address as default",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the secondary address to unset as default",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Secondary address removed from default successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function unsetDefault(Request $request, $id)
    {
        $user = $request->user();

        $address = UserAddress::where('user_id', $user->id)->findOrFail($id);
        $address->is_default = false;
        $address->save();

        return response()->json([
            'success' => true,
            'message' => 'Secondary address removed from default'
        ]);
    }

    /**
 * @OA\Get(
 *     path="/api/addresses/completeness",
 *     tags={"Addresses"},
 *     summary="Check if the authenticated user's active address is complete",
 *     description="Automatically checks the default address (secondary if set, otherwise main).",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Address completeness status",
 *         @OA\JsonContent(
 *             @OA\Property(property="success",             type="boolean"),
 *             @OA\Property(property="is_complete",         type="boolean"),
 *             @OA\Property(property="address_source",      type="string", example="main or secondary"),
 *             @OA\Property(property="missing_fields",      type="array", @OA\Items(type="string")),
 *             @OA\Property(property="recommended_fields",  type="array", @OA\Items(type="string")),
 *             @OA\Property(property="address",             type="object")
 *         )
 *     ),
 *     @OA\Response(response=401, description="Unauthenticated")
 * )
 */
    // public function checkCompleteness(Request $request)
    // {
    //     $user = $request->user();

    //     $secondaryDefault = UserAddress::where('user_id', $user->id)
    //         ->where('is_default', true)
    //         ->first();

    //     if ($secondaryDefault) {
    //         $address = [
    //             'region'          => $secondaryDefault->region,
    //             'city_id'         => $secondaryDefault->city_id,
    //             'rue'             => $secondaryDefault->street,
    //             'nom_batiment'    => $secondaryDefault->building_name,
    //             'etage'           => $secondaryDefault->floor,
    //             'num_appartement' => $secondaryDefault->apartment_number,
    //             'phone_number'    => $secondaryDefault->phone_number,
    //         ];
    //         $source = 'secondary';
    //     } else {
    //         $address = [
    //             'region'          => $user->region,
    //             'city_id'         => $user->city_id,
    //             'rue'             => $user->rue,
    //             'nom_batiment'    => $user->nom_batiment,
    //             'etage'           => $user->etage,
    //             'num_appartement' => $user->num_appartement,
    //             'phone_number'    => $user->phone_number,
    //         ];
    //         $source = 'main';
    //     }

    //     $requiredFields = [
    //         'region'       => 'Region',
    //         'rue'          => 'Street (Rue)',
    //         'nom_batiment' => 'Building name',
    //         'phone_number' => 'Phone number',
    //     ];

    //     $recommendedFields = [
    //         'city_id'         => 'City',
    //         'etage'           => 'Floor (Étage)',
    //         'num_appartement' => 'Apartment number',
    //     ];

    //     $missingRequired    = [];
    //     $missingRecommended = [];

    //     foreach ($requiredFields as $field => $label) {
    //         if (empty($address[$field])) {
    //             $missingRequired[] = $label;
    //         }
    //     }

    //     foreach ($recommendedFields as $field => $label) {
    //         if (empty($address[$field])) {
    //             $missingRecommended[] = $label;
    //         }
    //     }

    //     return response()->json([
    //         'success'            => true,
    //         'is_complete'        => empty($missingRequired),
    //         'address_source'     => $source,
    //         'missing_fields'     => $missingRequired,
    //         'recommended_fields' => $missingRecommended,
    //         'address'            => $address,
    //     ]);
    // }
    public function checkCompleteness(Request $request)
    {
        $user = $request->user();

        $secondaryDefault = UserAddress::where('user_id', $user->id)
            ->where('is_default', true)
            ->first();

        if ($secondaryDefault) {
            $address = [
                'region'          => $secondaryDefault->region,
                'city_id'         => $secondaryDefault->city_id,
                'rue'             => $secondaryDefault->street,
                'nom_batiment'    => $secondaryDefault->building_name,
                'etage'           => $secondaryDefault->floor,
                'num_appartement' => $secondaryDefault->apartment_number,
                'phone_number'    => $secondaryDefault->phone_number,
            ];
            $source = 'secondary';
        } else {
            $address = [
                'region'          => $user->region,
                'city_id'         => $user->city_id,
                'rue'             => $user->rue,
                'nom_batiment'    => $user->nom_batiment,
                'etage'           => $user->etage,
                'num_appartement' => $user->num_appartement,
                'phone_number'    => $user->phone_number,
            ];
            $source = 'main';
        }

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
            'address_source'     => $source,
            'missing_fields'     => $missingRequired,
            'recommended_fields' => $missingRecommended,
            'address'            => array_merge($address, [
                'etage_display' => $etageDisplay,
            ]),
        ]);
    }

}
