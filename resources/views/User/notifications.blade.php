@extends('User.fixe')
@section('titre', 'Mes notifications')
@section('content')
@section('body')

    <div class="container pt-5 pb-5">
        @livewire('User.Notifications')
    </div>
@endsection
