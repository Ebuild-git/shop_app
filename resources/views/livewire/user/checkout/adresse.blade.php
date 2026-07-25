@section('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media (max-width: 768px) {
            .modal-dialog {
                max-width: 90%;
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
    <div class="modal fade" id="location-modal" tabindex="-1" role="dialog" aria-labelledby="location-modal" aria-hidden="true" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
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
            {{-- <b class="color">
                {{ __("Choix de l'adresse de Livraison") }}
            </b> --}}
        </h3>
    </div>
    <br>
    <div class="row" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
        <div class="col-lg-6 col-md-8 col-12 mx-auto">

            <div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
                    <div class="modal-content rounded-3">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editAddressModalLabel">{{ __('Modifier l\'adresse de livraison') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning mb-3" role="alert">
                                {!! __("address_info_note") !!}
                            </div>
                            <form wire:submit.prevent="updateAddress">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="region" class="form-label">{{ __('Région') }}<span class="text-danger">*</span></label>
                                        <select id="region" wire:model="region" class="form-select modern-input" required>
                                            <option value="">{{ __('select_region') }}</option>
                                            @foreach($regions as $regionItem)
                                                <option value="{{ $regionItem->id }}">{{ __($regionItem->nom) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="city_id" class="form-label">{{ __('city') }}<span class="text-danger">*</span></label>
                                        <select id="city_id" wire:model="city_id" class="form-select modern-input">
                                            <option value="">{{ __('select_city') }}</option>
                                            @foreach($cities as $cityItem)
                                                <option value="{{ $cityItem->id }}">{{ $cityItem->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('city_id') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="rue" class="form-label">{{ __('rue') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control modern-input" id="rue" wire:model="rue">
                                        @error('rue') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="nom_batiment" class="form-label">{{ __('batiment') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control modern-input" id="nom_batiment" wire:model="nom_batiment">
                                        @error('nom_batiment') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="etage" class="form-label">{{ __('etage') }}</label>
                                        <input type="text" class="form-control modern-input" id="etage" wire:model="etage">
                                        @error('etage') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="num_appartement" class="form-label">{{ __('num_appartement') }}</label>
                                        <input type="text" class="form-control modern-input" id="num_appartement" wire:model="num_appartement">
                                        @error('num_appartement') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label for="phone_number" class="form-label">{{ __('telephone') }}</label>
                                        <div class="input-container d-flex align-items-center position-relative">
                                            @if (app()->getLocale() == 'ar')
                                                <input type="text" id="phone_number" class="form-control" wire:model="phone_number"
                                                    style="padding-right: 45px;" maxlength="14" oninput="formatTelephone(this)">
                                                <img src="/icons/maroc.webp" alt="Moroccan flag" class="flag-icon2" style="right: 10px; left: auto;">
                                            @else
                                                <img src="/icons/maroc.webp" alt="Moroccan flag" class="flag-icon2">
                                                <input type="text" id="phone_number" class="form-control" wire:model="phone_number" style="padding-left: 45px;" maxlength="14" oninput="formatTelephone(this)">
                                            @endif
                                        </div>
                                        @error('phone_number') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-black w-100">{{ __('save_button') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="saved-address mt-4 position-relative" id="saved-address">
                <div class="address-card1 p-3 shadow-sm">

                    <h5 class="address-title text-center mb-3">{{ __('current_address') }}</h5>

                    {{-- <button type="button" class="btn-modern-1 position-absolute" style="{{ app()->getLocale() == 'ar' ? 'bottom: 10px; left: 24px' : 'bottom: 10px; right: 10px;' }}" data-bs-toggle="modal" data-bs-target="#editAddressModal">
                        <i class="bi bi-pencil-square"></i>
                    </button> --}}
                    <a href="{{ url('informations?section=perso') }}" class="btn-modern-1 position-absolute" style="{{ app()->getLocale() == 'ar' ? 'bottom: 10px; left: 24px' : 'bottom: 10px; right: 10px;' }}">
                        <i class="bi bi-pencil-square"></i>
                    </a>

                    <div class="address-details">
                        <b class="h6 d-block mb-1">
                            {{ $user->gender == 'male' ? __('gender_male') : __('gender_female') }}
                            {{ ucfirst($user->firstname) }} {{ ucfirst($user->lastname) }}
                        </b>
                        <p class="mb-1">
                            @if ($user->city_id && $user->rue && $user->nom_batiment && $user->region_info)
                                {!! $user->num_appartement ? 'App. ' . $user->num_appartement . ', ' : '' !!}
                                {!! ($user->etage !== null && $user->etage !== '') ? 'Étage ' . $user->etage . ', ' : '' !!}
                                {!! $user->nom_batiment ? 'Résidence ' . $user->nom_batiment . ', ' : '' !!}
                                {!! $user->rue ? 'Rue ' . $user->rue . ', ' : '' !!}
                                {!! optional($user->city)->name ? 'Ville ' . $user->city->name . ', ' : '' !!}
                                {!! optional($user->region_info)->nom ? $user->region_info->nom : '' !!}
                            @else
                                {{ __('incomplete_address') }}
                            @endif
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-telephone"></i> {{ $user->phone_number }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                @php
                    $isPrimaryAddressComplete =
                        !empty($user->region) &&
                        !empty($user->city_id) &&
                        !empty($user->rue) &&
                        $user->etage !== null && $user->etage !== '' &&
                        !empty($user->nom_batiment) &&
                        $user->num_appartement !== null && $user->num_appartement !== '' &&
                        !empty($user->phone_number);
                @endphp

                @if ($isPrimaryAddressComplete)
                    <button type="button" wire:click="valider()" class="btn btn-dark btn-continue {{ app()->getLocale() == 'ar' ? 'rtl-left-arrow' : '' }}">
                        @if (app()->getLocale() == 'ar')
                            {{ __('continue') }} <i class="bi bi-arrow-left"></i>
                        @else
                            {{ __('continue') }} <i class="bi bi-arrow-right"></i>
                        @endif
                    </button>
                @else
                    <div class="alert alert-warning alert-clickable" data-scroll-to="saved-address">
                        {{ __('complete_main_address_info') }}
                    </div>
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
    document.addEventListener('livewire:init', function () {
        document.querySelectorAll('.alert-clickable').forEach(alert => {
            alert.style.cursor = 'pointer';
            alert.addEventListener('click', function () {
                const target = this.getAttribute('data-scroll-to');
                if (target === 'saved-address') {
                    const myModalEl = document.getElementById('editAddressModal');
                    const modal = new bootstrap.Modal(myModalEl);
                    modal.show();
                }
            });
        });
    });
</script>
