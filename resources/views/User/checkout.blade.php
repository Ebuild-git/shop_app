@extends('User.fixe')
@section('titre', "Finaliser l'achat")
@section('body')

<div class="container pt-5 pb-5">
    <h4>
        <b>Mon panier</b>
    </h4>
    <hr>
    @livewire('User.checkout')
</div>

@endsection
