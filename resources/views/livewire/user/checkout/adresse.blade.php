@section('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

@endsection

@section('modal')
    <!-- location-modal Modal -->
    <div class="modal fade" id="location-modal" tabindex="-1" role="dialog" aria-labelledby="location-modal" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content" id="location-modal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
        <div class="col-sm-6 mx-auto">
            <button type="button" class="btn btn-outline-dark w-100" data-bs-toggle="modal" data-bs-target="#editAddressModal">
                <i class="bi bi-pencil-square"></i> Modifier mon adresse de livraison
            </button>

            <div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editAddressModalLabel">Modifier l'adresse de livraison</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form wire:submit.prevent="updateAddress">
                                <div class="mb-3">
                                    <label for="region" class="form-label">Région<span class="text-danger">*</span></label>
                                    <select id="region" wire:model="region" class="custom-select" required>
                                        <option value="">Sélectionnez une région</option>
                                        @foreach($regions as $regionItem)
                                            <option value="{{ $regionItem->id }}">{{ $regionItem->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Adresse<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="address" wire:model="address">
                                    @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <button type="submit" class="btn btn-black">Enregistrer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <br><br>
            <button type="button" class="btn btn-dark w-100" onclick="get_location()">
                <i class="bi bi-geo-alt"></i>
                Utiliser ma localisation
            </button>
            <br>
            <br>
            <hr>
            <br>
            <h4 class="color">
                J'utilise cette adresse enregistrée
            </h4>
            <div>
                <div class="alert alert-dark">
                    <div class="text-black">
                        <b class="h6">
                            @if ($user->gender == 'male')
                                Mrs.
                            @else
                                Mme.
                            @endif
                            {{ $user->firstname }} {{ $user->lastname }}
                        </b>
                        <p>
                            @if ($user->address)
                                {{ $user->address }} <br>
                            @endif
                            @if ($user->region_info)
                                {{ $user->region_info->nom }} <br>
                            @endif
                            <i class="bi bi-telephone"></i>
                            {{ $user->phone_number }} <br>
                        </p>
                    </div>
                </div>
                <br>
                <div class="d-flex justify-content-end">
                    @if ($this->next)
                    <button type="button" wire:click="valider()" @disabled(!$user->address) class="btn btn-dark">
                        <b>Continuer</b>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                    @else
                        <div class="alert alert-warning">
                            Vous devez choisir une adresse de livraison pour poursuivre.
                        </div>
                    @endif
                </div>
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

