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
                    <div >
                        @livewire('User.Rating', ['user' => $user])
                    </div>
                    <br>
                    <div>
                        <p>
                            <i class="bi bi-calendar-check"></i> Membre dépuis les {{ $user->created_at }}
                        <br>
                            <i class="bi bi-envelope"></i> Email vérifié <b>{{ $user->photo_verified_at ? "Oui" : "Non"}} </b>
                        </p>
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

@endsection
