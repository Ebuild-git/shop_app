@extends('User.fixe')
@section('titre', 'Inscription')
@section('content')
@section('body')

    <div class="container pt-5 pb-5">
        <div class="row">
            <div class="col-sm-6 ">
                <div class="position-absolute">
                </div>
                <img src="https://supply-chain.net/wp-content/uploads/2023/01/Screenshot-2023-01-12-at-19-02-17-livreur-recevant-colis-pour-livraison_23-2149371921.jpg-Image-WEBP-626-%C3%97-417-pixels.png" class="img" alt="" srcset="">
            </div>
            <div class="col-sm-6 ">
                <h4>
                    <i> Vendez <span class="color-orange">Maintenant</span> !</i>
                 </h4>
                 <hr>
                 <h4>
                   <i>
                    Connexion
                   </i>
                 </h4>
                @livewire('User.connexion')
            </div>
        </div>
    </div>

    <style>
        .img{
            width: 100%;
            border-radius: 05%;
        }
    </style>
@endsection
