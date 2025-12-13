<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAddress;
use App\Models\regions;
use App\Models\User;

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

        $secondaryDefault = UserAddress::where('user_id', $user->id)
            ->where('is_default', true)
            ->first();

        $otherSecondary = UserAddress::where('user_id', $user->id)
            ->where('is_default', false)
            ->get();

        $mainAddress = [
            'region' => $user->region,
            'address' => $user->address,
            'rue' => $user->rue,
            'nom_batiment' => $user->nom_batiment,
            'etage' => $user->etage,
            'num_appartement' => $user->num_appartement,
            'phone_number' => $user->phone_number,
            'is_default' => false,
        ];

        if ($secondaryDefault) {
            $activeAddress = $secondaryDefault;
            $secondaryAddresses = $otherSecondary->push($mainAddress);
        } else {
            $activeAddress = (object) $mainAddress;
            $secondaryAddresses = $otherSecondary;
        }

        return response()->json([
            'success' => true,
            'active_address' => $activeAddress,
            'secondary_addresses' => $secondaryAddresses
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
            'address' => 'required|string|max:255',
            'rue' => 'required|string|max:255',
            'nom_batiment' => 'required|string|max:255',
            'etage' => 'nullable|string|max:50',
            'num_appartement' => 'nullable|string|max:50',
            'phone_number' => 'nullable|string|size:8',
        ],[
            'phone_number.size' => 'The phone number must be exactly 8 digits.',
        ]);

        $user->update([
            'region' => $validated['region'],
            'address' => $validated['address'],
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
                'address' => $user->address,
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
            'city' => 'required|string|max:255',
            'street' => 'nullable|string|max:255',
            'building_name' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:50',
            'apartment_number' => 'nullable|string|max:50',
            'phone_number' => 'nullable|string|max:20',
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
            'city' => 'sometimes|string|max:255',
            'street' => 'nullable|string|max:255',
            'building_name' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:50',
            'apartment_number' => 'nullable|string|max:50',
            'phone_number' => 'nullable|string|max:20',
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
}
