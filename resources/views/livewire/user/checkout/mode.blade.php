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
                        Choisir cette option pour payer à la livraison ( a l'adresse de votre choix ) et recevoir votre
                        commande.
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
                        Frais de livraison : 
                    </p>
                    <b>
                        0 DH
                    </b>
                </div>
                <div class="d-flex justify-content-between">
                    <h4 class="color">
                        <b>TOTAL : </b>
                    </h4>
                    <h4 class="h5">
                        <b>{{ $total }} DH</b>
                    </h4>
                </div>
                <hr>
                <div class="text-center">
                    <div class="mb-3">
                        <a href="{{ route('conditions') }}" class="color">
                            J'accepte les condition générale de ventes
                        </a>
                    </div>
                    <button type="button" class="btn btn-info w-100 bg-red" wire:click="confirm()">
                        <span wire:loading>
                            <x-Loading></x-Loading>
                        </span>
                        Passer la commande
                    </button>
                    <div class="mt-3">
                        <a href="{{ route('checkout') }}?step=2" class="color">
                            Retour aux adresses de livraison
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('checkout') }}?step=2" class="btn ">
            <i class="bi bi-arrow-left"></i> Adresse de livraison
        </a>
    </div>
</div>