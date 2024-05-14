@extends('User.fixe')
@section('titre', $user->username)
@section('content')
@section('body')

    <style>
        .card-ps {
            border: solid 1px #00808065;
            border-radius: 5px;
            display: inline-block;
        }

        .text-end {
            text-align: right !important;
        }
    </style>
    <!-- ======================= Filter Wrap Style 1 ======================== -->
    <section class="py-3 ">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                               <b> <a href="/">Accueil</a></b>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"> {{ $user->username }} </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- ============================= Filter Wrap ============================== -->


    <div class="container pb-3 pt-3">

        <div class="">
            <div class="row">
                <div class="col-sm-6">
                    <h2>
                        {{ $user->username }}
                    </h2>
                    <div data-toggle="modal" data-target="#noter">
                        @php
                            $count = number_format($user->averageRating->average_rating ?? 1);
                            $avis = $user->getReviewsAttribute->count();
                        @endphp
                        @if ($avis > 0)
                            <!-- Étoiles notées -->
                            @for ($i = 0; $i < $count; $i++)
                                <i class="bi bi-star-fill" style="color:#018d8d;"></i>
                            @endfor
                            <!-- Étoiles non notées -->
                            @for ($i = $count; $i < 5; $i++)
                                <i class="bi bi-star-fill" style="color:#828282;"></i>
                            @endfor
                        @else
                            <!-- 5 étoiles grises si pas d'avis -->
                            @for ($i = 0; $i < 5; $i++)
                                <i class="bi bi-star-fill" style="color:#828282;"></i>
                            @endfor
                        @endif
                        {{ $avis }} Avis
                    </div>
                </div>
                <div class="col-sm-6 text-end">

                    <div >
                        <div class="card-ps text-center p-2">
                            <div>
                                <img width="20" height="20" src="/icons/shopping-en-ligne.svg" alt="external" />
                            </div>
                            <b> {{ $user->GetPosts->count() }} </b> 
                            Annonces
                        </div>
                        <div class="card-ps text-center p-2">
                            <div>
                                <img width="20" height="20" src="/icons/sac-de-courses.svg" alt="sale" />
                            </div>
                            <b> {{ $user->total_sales ?? 0 }} </b>
                            Ventes
                        </div>
                        <div class="card-ps text-center p-2">
                            <div>
                                <img width="20" height="20" src="/icons/menu.svg" alt="category" />
                            </div>
                            <b> {{ $user->categoriesWhereUserPosted->count() }} </b>
                            Catégories
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>
       
        <hr>
        <div>
            <h4>
                {{ $user->GetPosts->count() }}  publications
            </h4>
        </div>
        <br>
        @livewire('User.ProfileAnnonces', ['user' => $user])
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
