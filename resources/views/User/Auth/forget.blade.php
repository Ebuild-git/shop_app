@extends('User.fixe')
@section('titre', 'Inscription')
@section('content')
@section('body')

    <div class="container pt-5 pb-5">
        <div class="col-sm-6 mx-auto card border border-1 p-3 ">
            <div class=" p-3 ">
                <div class="h3">
                    Mot de passe oublié
                </div>
            </div>
            <br>
            @livewire('User.ResetPassword')
        </div>
    </div>
@endsection
