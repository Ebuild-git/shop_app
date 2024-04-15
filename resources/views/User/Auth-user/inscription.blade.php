@extends('User.fixe')
@section('titre', 'Inscription')
@section('content')
@section('body')

    <div class="container pt-5 pb-5">
        <div class="row">
            <div class="col-sm-6 ">
                <div class="p-3 ">
                    <div class="p-3 ">
                        <h4>
                           <i> Débutez l'aventure !</i>
                        </h4>
                        <img style="width: 80%;" src="/icons/illus-register.png" alt="" srcset="">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 ">
                @livewire('User.Inscription')
            </div>
        </div>
    </div>
    
@endsection
