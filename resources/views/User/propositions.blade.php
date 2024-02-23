@extends('User.fixe')
@section('titre', 'Liste des propositions')
@section('content')
@section('body')

<div class="container pt-5 pb-5">
    
    <div class="d-flex justify-content-between">
        <div>
            <h5>
                {{ $post->titre }}
            </h5>
        </div>
        <div>
            <img src="/icons/logo-version-noire.png" style="height: 30px">
        </div>
    </div>
    <hr>
    @livewire('User.ListePropositions', ['post' => $post])
    
</div>
@endsection