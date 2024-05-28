@extends('User.fixe')
@section('titre', 'Inscription')
@section('content')
@section('body')

    <div class="container pt-5 pb-5">
        <div class="row">
            <div class="col-sm-6 ">
                <div class="position-absolute">
                </div>
                <img src="/icons/login.webp"
                    class="img" alt="" srcset="">
            </div>
            <div class="col-sm-6 ">
                <hr>
                <h4>
                    Connexion
                </h4>
                @livewire('User.connexion')
            </div>
        </div>
    </div>

    <style>
        .img {
            width: 100%;
            border-radius: 05%;
        }
    </style>
@endsection
