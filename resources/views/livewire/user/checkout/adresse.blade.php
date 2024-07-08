@section('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
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
            <a href="{{ route('mes_informations') }}" class="btn btn-outline-dark w-100">
                <i class="bi bi-pencil-square"></i>
                Modifier mon adresse de livraison
            </a>
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
                    <button type="button" wire:click="valider()" @disabled(!$user->address) class="btn btn-dark">
                        <b>Continuer</b>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
