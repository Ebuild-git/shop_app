@extends('User.fixe')
@section('titre', "Finaliser l'achat")
@section('body')

    <div class="bg-red">
        <div class="container">
            <fiv class="row">
                <div class="col-sm-10 mx-auto p-3">
                    <div class="d-flex justify-content-around timeline">
                        <div class="step @if ($step == 1) active @endif ">
                            <i class="bi bi-cart"></i>
                            Etape 1
                        </div>
                        <div class="step @if ($step == 2) active @endif">
                            <i class="bi bi-geo-alt"></i>
                            Etape 2
                        </div>
                        <div class="step @if ($step == 3) active @endif">
                            <i class="bi bi-wallet2"></i>
                            Etape 3
                        </div>
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

    </div>




    <style>
        .timeline .step {
            cursor: pointer;
        }

        .timeline .active {
            font-weight: bold;
            border: solid 1px white;
            padding: 5px 10px;
            border-radius: 5px;
            color: #018d8d;
            background-color: white;

        }
    </style>

@endsection
