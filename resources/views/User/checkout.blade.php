@extends('User.fixe')
@section('titre', "Finaliser l'achat")
@section('body')

    <div class="bg-red @if ($step == 4) d-none @endif">
        <div class="container">
            <fiv class="row">
                <div class="col-sm-10 mx-auto p-3">
                    <div class="d-flex justify-content-around timeline">
                        <a href="{{ route('checkout') }}?step=1"
                            class="step @if ($step == 1) active @endif ">
                            <i class="bi bi-cart"></i>
                            Panier
                        </a>
                        <a href="{{ route('checkout') }}?step=2" class="step @if ($step == 2) active @endif">
                            <i class="bi bi-geo-alt"></i>
                            Adresse de livraison
                        </a>
                        <a href="{{ route('checkout') }}?step=3"
                            class="step @if ($step == 3) active @endif">
                            <i class="bi bi-wallet2"></i>
                            Paiement
                        </a>
                    </div>
                </div>
            </fiv>
        </div>
    </div>

    <div class="container pt-5 pb-5">
        @if ($step == 1)
            @livewire('User.Checkout.Panier')
        @endif

        @if ($step == 2)
            @livewire('User.Checkout.Adresse')
        @endif

        @if ($step == 3)
            @livewire('User.Checkout.Mode')
        @endif

        @if ($step == 4)
            <div class="mt-5 mb-5">
                <div class="text-center">
                    <h2 class="color">
                        <img src="/icons/icons8-coche.gif" alt="" srcset=""> <br>
                        Merci pour votre commande
                    </h2>
                    <p>
                        Vous allez recevoir un email de confirmation conprenent un recapitulatif de votre commande.
                    </p>
                    <a href="/" class="btn btn-outline-dark">
                        <i class="bi bi-house-door-fill"></i>
                        Retour à la page d'accueil
                    </a>
                </div>
            </div>
        @endif

    </div>




    <style>
        .timeline .step {
            cursor: pointer;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .timeline .step:hover {
            border: solid 1px white;
        }

        .timeline .active {
            font-weight: bold;
            border: solid 1px white;
            color: #018d8d;
            background-color: white;

        }
    </style>

@endsection
