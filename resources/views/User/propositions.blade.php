@extends('User.fixe')
@section('titre', 'Liste des propositions')
@section('content')
@section('body')

<div class="container pt-5 pb-5">
    
    
    @livewire('User.ListePropositions', ['post' => $post])
    
</div>
@endsection