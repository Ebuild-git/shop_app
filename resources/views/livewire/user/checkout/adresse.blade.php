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
                <div class="modal-dialog">
                    <div class="modal-content rounded-3">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editAddressModalLabel">Modifier l'adresse de livraison</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form wire:submit.prevent="updateAddress">
                                <div class="mb-3">
                                    <label for="region" class="form-label">Région<span class="text-danger">*</span></label>
                                    <select id="region" wire:model="region" class="form-select modern-input" required>
                                        <option value="">Sélectionnez une région</option>
                                        @foreach($regions as $regionItem)
                                            <option value="{{ $regionItem->id }}">{{ $regionItem->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Ville<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control modern-input" id="address" wire:model="address">
                                    @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="rue" class="form-label">Rue<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control modern-input" id="rue" wire:model="rue">
                                    @error('rue') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="nom_batiment" class="form-label">Nom Bâtiment<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control modern-input" id="nom_batiment" wire:model="nom_batiment">
                                    @error('nom_batiment') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <button type="submit" class="btn btn-black w-100">Enregistrer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="saved-address mt-4 position-relative">
                <div class="address-card p-3 shadow-sm">
                    <button type="button" class="btn-modern-1 position-absolute" style="bottom: 10px; right: 10px;" data-bs-toggle="modal" data-bs-target="#editAddressModal">
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    <h5 class="address-title text-center mb-3">Adresse de livraison actuelle</h5>

                    <!-- Check if any extra address is default -->
                    @php
                        $defaultAddress = $userAddresses->where('is_default', true)->first();
                    @endphp

                    @if ($defaultAddress)
                        <!-- Show Default Extra Address (from userAddresses table) -->
                        <div class="address-details">
                            <b class="h6 d-block mb-1">
                                {{ $defaultAddress->building_name ? $defaultAddress->building_name . ',' : '' }}
                                {{ $defaultAddress->street ? $defaultAddress->street . ',' : '' }}
                                {{ $defaultAddress->floor ? $defaultAddress->floor . ',' : '' }}
                                {{ $defaultAddress->apartment_number ? $defaultAddress->apartment_number . ',' : '' }}
                                {{ $defaultAddress->city ? $defaultAddress->city . ',' : '' }}
                                {{ optional($defaultAddress->regionExtra)->nom ? $defaultAddress->regionExtra->nom : '' }}
                            </b>
                            <p class="mb-0">
                                <i class="bi bi-telephone"></i> {{ $defaultAddress->phone_number }}
                            </p>
                            {{-- <button class="btn custom-default btn-sm mt-3" wire:click="removeDefault">
                                <i class="bi bi-arrow-counterclockwise"></i> Revenir à l'adresse par défaut
                            </button> --}}
                        </div>
                    @else
                        <!-- Show User's Address (from users table) -->
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
                                    {{ $user->address }}, {{ $user->rue }} {{ $user->nom_batiment }}, {{ $user->region_info->nom }}
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

                <!-- Show User's Address Here Even If Not Default -->

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
                                {{ $user->address }}, {{ $user->rue }} {{ $user->nom_batiment }}, {{ $user->region_info->nom }}
                            @else
                                Adresse non complète
                            @endif
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-telephone"></i> {{ $user->phone_number }}
                        </p>
                        <button class="btn custom-default btn-sm mt-3" wire:click="removeDefault">
                            <i class="bi bi-arrow-counterclockwise"></i> Revenir à l'adresse par défaut
                        </button>
                    </div>

                @endif
                    @if ($address->is_default)
                        <!-- Skip the default address since it's already shown above -->
                        @continue
                    @endif

                    <div class="address-card p-3 shadow-sm mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <b class="h6 mb-1">
                                {{ $address->building_name ? $address->building_name . ',' : '' }}
                                {{ $address->street ? $address->street . ',' : '' }}
                                {{ $address->floor ? $address->floor . ',' : '' }}
                                {{ $address->apartment_number ? $address->apartment_number . ',' : '' }}
                                {{ $address->city ? $address->city . ',' : '' }}
                                {{ optional($address->regionExtra)->nom ? $address->regionExtra->nom : '' }}
                            </b>
                            @if ($address->is_default)
                                <span class="badge" style="background-color: darkcyan;">Adresse par défaut</span>
                            @endif
                        </div>
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
                <div class="modal-dialog">
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

                                    <!-- Phone Number Input -->
                                    <div class="form-group">
                                        <label for="extraPhoneNumber" class="form-label">Numéro de téléphone</label>
                                        <input type="text" id="extraPhoneNumber" class="form-control" wire:model="extraPhoneNumber">
                                        @error('extraPhoneNumber') <span class="error">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-dark btn-continue" id="modalSubmitBtn">{{ $isEditMode ? 'Modifier' : 'Ajouter' }}</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>


            <!-- Continue Button -->
            <div class="d-flex justify-content-end mt-3">
                @if ($this->next)
                    <button type="button" wire:click="valider()" class="btn btn-dark btn-continue" @disabled(!($user->address && $user->rue && $user->nom_batiment))>
                        Continuer <i class="bi bi-arrow-right"></i>
                    </button>
                @else
                    <div class="alert alert-warning">
                        Veuillez compléter les informations manquantes de votre adresse pour continuer la commande.
                    </div>
                @endif
            </div>
        </div>
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

