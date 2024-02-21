@extends('User.fixe')
@section('titre', 'Inscription')
@section('content')
@section('body')

    <div class="container pt-5 pb-5">
        <div class="col-sm-6 mx-auto">
            <div  class="bg-red p-3 rounded">
                <h4 class="text-center">
                    INSCRIPTION
                </h4>
                <div class="text-center small">
                    En vous connectant vous accepter les termes et conditions et la politique de confidentialité..
                </div>
            </div>
            <br>
            @livewire('User.Inscription')
        </div>
    </div>
@endsection
