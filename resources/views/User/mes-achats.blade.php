@extends('User.fixe')
@section('titre', 'Créer une publication')
@section('content')
@section('body')

<div class="container pt-5 pb-5">
    
    <div class="card">
        @livewire('User.CreatePost', ['id' => $id ?? ""])
    </div>
    
</div>
@endsection