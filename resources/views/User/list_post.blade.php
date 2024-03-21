@extends('User.fixe')
@section('titre', 'Mes publications')
@section('body')

<div class="container pt-5 pb-5">

    @livewire('User.ListMesPosts', ["titre"=>"Mes publications","filter"=>true,"statut"=>null])
    
</div>
@endsection