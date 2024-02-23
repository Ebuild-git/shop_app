@extends('User.fixe')
@section('titre', 'Mes publications')
@section('content')
@section('body')

<div class="container-fluid pt-5 pb-5">
   

    @livewire('User.ListMesPosts')
    
</div>
@endsection