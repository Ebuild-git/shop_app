@section('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media (max-width: 768px) {
            /* Custom styles for tablets and mobile */
            .modal-dialog {
                max-width: 90%; /* Adjust modal width for smaller screens */
            }
            .modal-content {
                padding: 15px;
            }
            .text-center h3, .text-center h4 {
                font-size: 1.5rem;
            }
            .btn {
                font-size: 1rem;
            }
            .alert {
                font-size: 0.9rem;
            }

            /* Adjustments to padding and margins */
            .mb-3, .my-3 {
                margin-bottom: 15px;
            }
            .mt-3 {
                margin-top: 15px;
            }
        }
    </style>
@endsection

@section('modal')
    <!-- location-modal Modal -->
    <div class="modal fade" id="location-modal" tabindex="-1" role="dialog" aria-labelledby="location-modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" id="location-modal">
                <div class="modal-headers">
                    <button type="button" class="close" onclick="closeLocationModal()" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <center>
                        <h5>
                            <b class="color">
                                <i class="bi bi-geo-alt"></i>
                                Votre localisation
                            </b>
                        </h5>
                    </center>
                    <br>
                    <div id="map-adresse" class="map-adresse"></div>
                    <br>
                    <div id="val-adresse"></div>
                    <br>
                    <div class="text-center">
                        <button class="btn bg-red" id="btn-accept-location" onclick="btn_accept_location()">
                            <i class="bi bi-check2-square"></i>
                            Accepter cette localisation
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- End Modal -->
@endsection


<div>
    <div class="text-center">
        <h3>
            <b class="color">
                Choix de l'adresse de Livraison
            </b>
        </h3>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-6 col-md-8 col-12 mx-auto">
            <div class="d-flex-buttons mb-3">
                <button type="button" class="btn btn-dark btn-modern w-100" onclick="get_location()">
                    <i class="bi bi-geo-alt"></i> Utiliser ma localisation
                </button>
            </div>

            <!-- Edit Address Modal -->

            <div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg"> <!-- Optionally increase the size of the modal -->
                    <div class="modal-content rounded-3">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editAddressModalLabel">Modifier l'adresse de livraison</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning mb-3" role="alert">
                                <strong><i class="bi bi-info-circle"></i>Note:</strong> Vous modifiez l'adresse principale de votre compte.
                                Les modifications apportées à cette adresse mettront à jour vos informations d'adresse dans <b>"Mon compte"</b>.
                            </div>
                            <form wire:submit.prevent="updateAddress">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="region" class="form-label">Région<span class="text-danger">*</span></label>
                                        <select id="region" wire:model="region" class="form-select modern-input" required>
                                            <option value="">Sélectionnez une région</option>
                                            @foreach($regions as $regionItem)
                                                <option value="{{ $regionItem->id }}">{{ $regionItem->nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="address" class="form-label">Ville<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control modern-input" id="address" wire:model="address">
                                        @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="rue" class="form-label">Rue<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control modern-input" id="rue" wire:model="rue">
                                        @error('rue') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="nom_batiment" class="form-label">Nom Bâtiment<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control modern-input" id="nom_batiment" wire:model="nom_batiment">
                                        @error('nom_batiment') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="etage" class="form-label">Étage</label>
                                        <input type="text" class="form-control modern-input" id="etage" wire:model="etage">
                                        @error('etage') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="num_appartement" class="form-label">Numéro d'Appartement</label>
                                        <input type="text" class="form-control modern-input" id="num_appartement" wire:model="num_appartement">
                                        @error('num_appartement') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="phone_number" class="form-label">Numéro de Téléphone</label>
                                        <input type="text" class="form-control modern-input" id="phone_number" wire:model="phone_number" maxlength="14" oninput="formatPhoneNumber(this)">
                                        @error('phone_number') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-black w-100">Enregistrer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="saved-address mt-4 position-relative">
                <div class="address-card p-3 shadow-sm">


                    <h5 class="address-title text-center mb-3">Adresse de livraison actuelle</h5>

                    <!-- Check if any extra address is default -->
                    @php
                        $defaultAddress = $userAddresses->where('is_default', true)->first();
                    @endphp

                    @if ($defaultAddress)
                        <!-- Show Default Extra Address (from userAddresses table) -->
                        <div class="address-details">
                            <b class="h6 d-block mb-1">
                                @if ($user->gender == 'male')
                                    M.
                                @else
                                    Mme
                                @endif
                                {{ ucfirst($user->firstname) }} {{ ucfirst($user->lastname) }}
                            </b>
                            <p class="mb-1">
                                {{ $defaultAddress->building_name ? $defaultAddress->building_name . ',' : '' }}
                                {{ $defaultAddress->street ? $defaultAddress->street . ',' : '' }}
                                {{ $defaultAddress->floor ? 'Étage ' . $defaultAddress->floor . ',' : '' }}
                                {{ $defaultAddress->apartment_number ? 'Numéro d\'appartement ' . $defaultAddress->apartment_number . ',' : '' }}
                                {{ $defaultAddress->city ? $defaultAddress->city . ',' : '' }}
                                {{ optional($defaultAddress->regionExtra)->nom ? $defaultAddress->regionExtra->nom : '' }}
                            </p>
                            <p class="mb-0">
                                <i class="bi bi-telephone"></i> {{ $defaultAddress->phone_number }}
                            </p>
                            {{-- <button class="btn custom-default btn-sm mt-3" wire:click="removeDefault">
                                <i class="bi bi-arrow-counterclockwise"></i> Revenir à l'adresse par défaut
                            </button> --}}

                        </div>
                        <div class="mt-auto d-flex justify-content-end">
                            <button class="btn custom-edit btn-sm me-2 edit-address-btn"
                                    wire:click="prepareForUpdate({{ $defaultAddress->id }})"
                                    data-bs-toggle="modal"
                                    data-bs-target="#extraAddressModal"
                                    data-region="{{ $defaultAddress->region }}"
                                    data-city="{{ $defaultAddress->city }}"
                                    data-street="{{ $defaultAddress->street }}"
                                    data-building="{{ $defaultAddress->building_name }}"
                                    data-floor="{{ $defaultAddress->floor }}"
                                    data-apartment="{{ $defaultAddress->apartment_number }}"
                                    data-phone="{{ $defaultAddress->phone_number }}"
                                    onclick="populateModal(this)">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn custom-delete btn-sm" wire:click="deleteAddress({{ $defaultAddress->id }})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    @else
                        <!-- Show User's Address (from users table) -->
                        <button type="button" class="btn-modern-1 position-absolute" style="bottom: 10px; right: 10px;" data-bs-toggle="modal" data-bs-target="#editAddressModal">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <div class="address-details">
                            <b class="h6 d-block mb-1">
                                @if ($user->gender == 'male')
                                    M.
                                @else
                                    Mme
                                @endif
                                {{ ucfirst($user->firstname) }} {{ ucfirst($user->lastname) }}
                            </b>
                            <p class="mb-1">
                                @if ($user->address && $user->rue && $user->nom_batiment && $user->region_info)
                                {{ $user->nom_batiment ? $user->nom_batiment . ', ' : '' }}
                                {{ $user->rue ? $user->rue . ', ' : '' }}
                                {{ $user->etage ? 'Étage ' . $user->etage . ', ' : '' }}
                                {{ $user->num_appartement ? 'Numéro d\'appartement ' . $user->num_appartement . ', ' : '' }}
                                {{ $user->address ? $user->address . ', ' : '' }}
                                {{ optional($user->region_info)->nom ? $user->region_info->nom : '' }}
                                @else
                                    Adresse non complète
                                @endif
                            </p>
                            <p class="mb-0">
                                <i class="bi bi-telephone"></i> {{ $user->phone_number }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <hr style="border-color: #807e7e; border-width: 1px;" class="my-4">

            <!-- Extra Addresses Section -->
            <div class="extra-addresses mt-4">
                <div class="d-flex align-items-center mb-3">
                    <h5 class="mb-0">Autres adresses de livraison</h5>
                    <button class="add-new" data-bs-toggle="modal" data-bs-target="#extraAddressModal" wire:click="prepareForAdd">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>

                <!-- Loop through other extra addresses -->
                @foreach ($userAddresses as $address)
                @if ($address->is_default)
                    <div class="address-card p-3 shadow-sm mb-3">

                        <div class="d-flex justify-content-between align-items-center">
                            <b class="h6 mb-1">
                                @if ($user->gender == 'male')
                                    M.
                                @else
                                    Mme
                                @endif
                                {{ ucfirst($user->firstname) }} {{ ucfirst($user->lastname) }}
                            </b>
                            @if (!$defaultAddress)
                                <span class="badge" style="background-color: darkcyan;">Adresse par défaut</span>
                            @endif
                        </div>
                        <p class="mb-1">
                            @if ($user->address && $user->rue && $user->nom_batiment && $user->region_info)
                            {{ $user->nom_batiment ? $user->nom_batiment . ', ' : '' }}
                            {{ $user->rue ? $user->rue . ', ' : '' }}
                            {{ $user->etage ? 'Étage ' . $user->etage . ', ' : '' }}
                            {{ $user->num_appartement ? 'Numéro d\'appartement ' . $user->num_appartement . ', ' : '' }}
                            {{ $user->address ? $user->address . ', ' : '' }}
                            {{ optional($user->region_info)->nom ? $user->region_info->nom : '' }}

                            @else
                                Adresse non complète
                            @endif
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-telephone"></i> {{ $user->phone_number }}
                        </p>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button class="btn custom-default btn-sm" wire:click="removeDefault">
                                <i class="bi bi-arrow-counterclockwise"></i> Définir par défaut
                            </button>
                            <button type="button" class="btn-modern-1" data-bs-toggle="modal" data-bs-target="#editAddressModal">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </div>


                    </div>

                @endif
                    @if ($address->is_default)
                        <!-- Skip the default address since it's already shown above -->
                        @continue
                    @endif

                    <div class="address-card p-3 shadow-sm mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <b class="h6 mb-1">
                                @if ($user->gender == 'male')
                                    M.
                                @else
                                    Mme
                                @endif
                                {{ ucfirst($user->firstname) }} {{ ucfirst($user->lastname) }}
                            </b>

                            @if ($address->is_default)
                                <span class="badge" style="background-color: darkcyan;">Adresse par défaut</span>
                            @endif
                        </div>
                        <p class="mb-1">
                            {{ $address->building_name ? $address->building_name . ',' : '' }}
                            {{ $address->street ? $address->street . ',' : '' }}
                            {{ $address->floor ? 'Étage ' . $address->floor . ',' : '' }}
                            {{ $address->apartment_number ? 'Numéro d\'appartement ' . $address->apartment_number . ',' : '' }}
                            {{ $address->city ? $address->city . ',' : '' }}
                            {{ optional($address->regionExtra)->nom ? $address->regionExtra->nom : '' }}
                        </p>
                        <p class="mb-0"><i class="bi bi-telephone" style="color: teal;"></i> {{ $address->phone_number }}</p>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                @if (!$address->is_default)
                                    <button class="btn custom-default btn-sm" wire:click="setDefault({{ $address->id }})">
                                        <i class="fa fa-map-pin"></i> Définir par défaut
                                    </button>
                                @endif
                            </div>
                            <div class="d-flex">
                                <button class="btn custom-edit btn-sm me-2 edit-address-btn"
                                        wire:click="prepareForUpdate({{ $address->id }})"
                                        data-bs-toggle="modal"
                                        data-bs-target="#extraAddressModal"
                                        data-region="{{ $address->region }}"
                                        data-city="{{ $address->city }}"
                                        data-street="{{ $address->street }}"
                                        data-building="{{ $address->building_name }}"
                                        data-floor="{{ $address->floor }}"
                                        data-apartment="{{ $address->apartment_number }}"
                                        data-phone="{{ $address->phone_number }}"
                                        onclick="populateModal(this)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn custom-delete btn-sm" wire:click="deleteAddress({{ $address->id }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>


            <!-- Unified Add/Edit Extra Address Modal -->
            <div class="modal fade" id="extraAddressModal" wire:ignore.self tabindex="-1" aria-labelledby="extraAddressModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content rounded-3">
                        <div class="modal-header">
                            <h5 class="modal-title" id="extraAddressModalLabel">
                                {{ $isEditMode ? 'Modifier l\'adresse' : 'Ajouter une nouvelle adresse' }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form wire:submit.prevent="{{ $isEditMode ? 'saveAddress' : 'saveAddress' }}" class="form-grid">
                                <div class="grid-container">
                                    <!-- Region Select -->
                                    <div class="form-group">
                                        <label for="extraRegion">Région</label>
                                        <select wire:model="extraRegion" id="extraRegion" class="form-control">
                                            <option value="">Sélectionnez une région</option>
                                            @foreach($regions as $regionItem)
                                                <option value="{{ $regionItem->id }}">{{ $regionItem->nom }}</option>
                                            @endforeach
                                        </select>
                                        @error('extraRegion') <span class="error">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- City Input -->
                                    <div class="form-group">
                                        <label for="extraCity" class="form-label">Ville<span class="text-danger">*</span></label>
                                        <input type="text" id="extraCity" class="form-control" wire:model="extraCity" required>
                                        @error('extraCity') <span class="error">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Street Input -->
                                    <div class="form-group">
                                        <label for="extraStreet" class="form-label">Rue</label>
                                        <input type="text" id="extraStreet" class="form-control" wire:model="extraStreet">
                                        @error('extraStreet') <span class="error">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Building Name Input -->
                                    <div class="form-group">
                                        <label for="extraBuilding" class="form-label">Nom Bâtiment</label>
                                        <input type="text" id="extraBuilding" class="form-control" wire:model="extraBuilding">
                                        @error('extraBuilding') <span class="error">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Floor Input -->
                                    <div class="form-group">
                                        <label for="extraFloor" class="form-label">Étage</label>
                                        <input type="text" id="extraFloor" class="form-control" wire:model="extraFloor">
                                        @error('extraFloor') <span class="error">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Apartment Number Input -->
                                    <div class="form-group">
                                        <label for="extraApartment" class="form-label">Numéro d'appartement</label>
                                        <input type="text" id="extraApartment" class="form-control" wire:model="extraApartment">
                                        @error('extraApartment') <span class="error">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group position-relative">
                                        <label for="extraPhoneNumber" class="form-label">Numéro de téléphone</label>
                                        <div class="input-container d-flex align-items-center position-relative">
                                            <img src="/icons/maroc.webp" alt="Moroccan flag" class="flag-icon2">
                                            <input type="text" id="extraPhoneNumber" class="form-control" wire:model="extraPhoneNumber" style="padding-left: 45px;" maxlength="14" oninput="formatTelephone(this)">
                                        </div>
                                        @error('extraPhoneNumber') <span class="error">{{ $message }}</span> @enderror
                                    </div>


                                </div>

                                <!-- Submit Button -->
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-dark btn-continue" id="modalSubmitBtn">{{ $isEditMode ? 'Enregistrer' : 'Ajouter' }}</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>


            <div class="d-flex justify-content-end mt-3">
                @php
                    // Check if there is a default extra address and validate it
                    $isDefaultExtraAddressComplete = $defaultAddress &&
                        !empty($defaultAddress->region) &&
                        !empty($defaultAddress->city) &&
                        !empty($defaultAddress->street) &&
                        !empty($defaultAddress->building_name) &&
                        !empty($defaultAddress->floor) &&
                        !empty($defaultAddress->apartment_number) &&
                        !empty($defaultAddress->phone_number);

                    // Check completeness for the primary address only if no default extra address is set
                    $isPrimaryAddressComplete = !$defaultAddress &&
                        !empty($user->region) &&
                        !empty($user->address) &&
                        !empty($user->rue) && !empty($user->etage) &&
                        !empty($user->nom_batiment) && !empty($user->num_appartement) &&
                        !empty($user->phone_number);
                @endphp

                @if ($defaultAddress)
                    @if ($isDefaultExtraAddressComplete)
                        <button type="button" wire:click="valider()" class="btn btn-dark btn-continue">
                            Continuer <i class="bi bi-arrow-right"></i>
                        </button>
                    @else
                        <div class="alert alert-warning">
                            Veuillez compléter toutes les informations de l'adresse par défaut pour continuer la commande.
                        </div>
                    @endif
                @else
                    @if ($isPrimaryAddressComplete)
                        <button type="button" wire:click="valider()" class="btn btn-dark btn-continue">
                            Continuer <i class="bi bi-arrow-right"></i>
                        </button>
                    @else
                        <div class="alert alert-warning">
                            Veuillez compléter toutes les informations de votre adresse principale pour continuer la commande.
                        </div>
                    @endif
                @endif
            </div>


        </div>
    </div>

    <div id="loader" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        Loading...
    </div>
</div>

 <!-- Bootstrap JS and dependencies -->
 <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 @livewireScripts

 <script>
     window.addEventListener('addressUpdated', event => {
         var myModalEl = document.getElementById('editAddressModal');
         var modal = bootstrap.Modal.getInstance(myModalEl);
         if (modal) {
             modal.hide();
         }
         alert('Adresse modifiée avec succès.');
     });
 </script>


<script>
    document.addEventListener('refreshAddresses', () => {
        // Show the loader
        document.getElementById('loader').style.display = 'block';

        // Reload the page
        setTimeout(() => {
            location.reload();
        }, 50);
    });
</script>

