<div>
    <div class="text-center">
        <h3>
            <b class="color">
                Mode de paiement & livraison
            </b>
        </h3>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-8 mx-auto">
            <div>
                <div class="card p-3">
                    <h5>
                        <b>
                            <i class="bi bi-house"></i>
                            Paiement a la livraison
                        </b>
                    </h5>
                    <p>
                        Vous payez à la livraison ( à l'adresse de votre choix ) et recevez votre
                        commande
                    </p>
                </div>
                <br>
                <b>
                    <i class="bi bi-geo-alt"></i> Adresse de facturation
                </b>
                <div>
                    <div class="alert alert-dark">
                        <i class="bi bi-geo-alt"></i>
                        {{ $user->address }}
                        <br>
                        <b> <i class="bi bi-geo-alt"></i> {{ $user->region_info->nom }} </b>
                        <br>
                        <i class="bi bi-telephone"></i>
                        {{ $user->phone_number }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4 mx-auto">
            <div class="alert alert-dark">
                <div class="d-flex justify-content-between mb-3">
                    <b>Total des produits : </b>
                    <b>{{ $nbre_article }}</b>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p>
                       Total des Frais de livraison :
                    </p>
                    <b>
                        {{ $frais * $nbre_article }} <sup>DH</sup>
                    </b>
                </div>
                <div class="d-flex justify-content-between">
                    <h4 class="color">
                        <b>TOTAL : </b>
                    </h4>
                    <h4 class="h5">
                        <b>{{ number_format($total, 2, '.', '') + $frais * $nbre_article}} <sup>DH</sup></b>
                    </h4>
                </div>
                @if ($total > 0)
                    <hr>
                    <div class="text-center">
                        <div class="mb-3">
                            <div>
                                <p class="text-center pr-5 pl-5">
                                    En poursuivant votre commande, vous acceptez les
                                    <span  data-toggle="modal" data-target="#conditions" class="color cusor">
                                        <b>Conditions générales</b>
                                    </span> de SHOPIN
                                </p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-info w-100 bg-red" wire:click="confirm()">
                            <span wire:loading>
                                <x-Loading></x-Loading>
                            </span>
                            Valider mon panier
                        </button>
                        <div class="mt-3">
                            <a href="{{ route('checkout') }}?step=2" class="color">
                                Retour aux adresses de livraison
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('checkout') }}?step=2" class="btn ">
            <i class="bi bi-arrow-left"></i> Adresse de livraison
        </a>
    </div>
    @section('script')
        <script>
            $(document).ready(function() {
                $("#show_conditions").click(function() {
                    ('#conditions').modal('show');
                });
            });
        </script>
    @endsection

</div>
