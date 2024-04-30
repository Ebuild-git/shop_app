@extends('User.fixe')
@section('titre', $user->name)
@section('content')
@section('body')

    <!-- ======================= Filter Wrap Style 1 ======================== -->
    <section class="py-3 br-bottom br-top">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/">Accueil</a>
                            </li>
                            <li class="breadcrumb-item " aria-current="page">Utilisateur</li>
                            <li class="breadcrumb-item active" aria-current="page">   {{ '@'.$user->username }} </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- ============================= Filter Wrap ============================== -->


    <div class="container pb-3 pt-3">
        <div class="row">
            <div class="col-sm-3 ">
                <div class="card p-3">
                    <div class="position-relative">
                        <button class="btn-start-profile" type="button" data-toggle="modal" data-target="#noter">
                            <img width="24" height="24"
                                src="https://img.icons8.com/external-zen-filled-royyan-wijaya/24/FAB005/external-stars-astronomy-zen-filled-royyan-wijaya.png"
                                alt="external-stars-astronomy-zen-filled-royyan-wijaya" />
                            <b>Noter</b>
                        </button>
                        <div class="pt-1 pb-1">
                            <span class="h5 text-capitalize">
                                {{ '@'.$user->username }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <i class="bi bi-card-list"></i>
                        {{ $user->GetPosts->count() }} Annonces <br>
                        <i class="bi bi-geo-alt"></i>
                        {{ $user->adress ?? 'N/A' }} <br>
                        <i class="bi bi-house"></i>
                        {{ $user->region->nom ?? 'N/A' }}<br>
                        <div style="text-align: right;" style="background-color: #018d8d;">
                            <b>Note :</b>
                            {{ number_format($user->averageRating->average_rating ?? 0, 1) }}
                            <i class="bi bi-star-fill" style="color: #fab005;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-9">
                @livewire('User.ProfileAnnonces', ['user' => $user])
            </div>
        </div>
    </div>


    <!-- Log In Modal -->
    <div class="modal fade" id="noter" tabindex="1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>

                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <h2 class=" h5">
                            Votre avis compte !
                        </h2>
                    </div>
                    <p>
                        Votre opinion est précieuse pour nous. Aidez-nous à améliorer notre service en partageant votre
                        expérience avec d'autres utilisateurs. Veuillez prendre un moment pour évaluer votre interaction
                        avec {{ $user->username }} en lui attribuant une note de 1 à 5 étoiles.
                    </p>
                    <hr>
                    @auth
                        @livewire('User.Rating', ['user' => $user])
                    @endauth
                    @guest
                        <div class="alert alert-danger">
                            Pour ajouter un avis, vous devez être connecté(e).
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->


    <style>
        .avatar-profil {
            height: 100px;
            width: 100px;
            border-radius: 100%
        }

        .couverture {
            background: url("https://cdn.pixabay.com/photo/2014/02/27/16/10/flowers-276014_640.jpg")no-repeat;
            background-size: cover;
            height: 300px;
        }
    </style>
    <br><br><br>
@endsection
