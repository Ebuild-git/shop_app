@extends('User.fixe')
@section('titre', 'Inscription')
@section('content')
@section('body')

    <div class="container pt-5 pb-5">
        <div class="row">
            <div class="col-sm-6 mx-auto ">
               <div class="card p-3 ">
                <div class="bg-red p-3  rounded">
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="/connexion">
                                <button class="back-btn shadow-none">
                                    <i class="bi bi-arrow-left-circle"></i>
                                </button>
                            </a>
                        </div>
                        <div>
                            <h4 class="text-center">
                                <img src="/icons/logo-version-blanc.png"height="30" alt="">
                            </h4>
                        </div>
                        <div></div>
                    </div>
                    <br>
                    <div class="text-center small">
                        En vous inscrivant vous accepter les termes et conditions et la politique de confidentialité..
                    </div>
                </div>
                <br>
                @livewire('User.Inscription')
               </div>
            </div>
        </div>
    </div>
@endsection
