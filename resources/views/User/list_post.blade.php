@extends('User.fixe')
@section('titre', 'Mes annonces')
@section('body')

<div class="container pt-5 pb-5">

    @livewire('User.ListMesPosts', ["titre"=>"Mes annonces","filter"=>true,"statut"=>null])
    
</div>
@endsection