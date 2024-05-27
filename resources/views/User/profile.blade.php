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
                               <a href="/">Accueil</a>
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
                <div class="col-sm-4">
                    <div>
                        <table>
                            <tr>
                                <td>
                                    <div class="avatar-shopinner-details">
                                        <img src="{{ $user->getAvatar() }}" alt="avatar" height="80" srcset="">
                                    </div>
                                </td>
                                <td>
                                    <h4 class="h6">
                                        <a href="/user/{{ $user->id }}" class="h4">
                                            {{ $user->username }}
                                        </a>
                                    </h4>
                                    <div>
                                        @php
                                            $count = number_format($user->averageRating->average_rating ?? 1);
                                            $avis = $user->getReviewsAttribute->count();
                                        @endphp

                                        
                                        @livewire('User.Rating', ['user' => $user])
                                        <div>
                                            <span>
                                                <b>{{ $user->total_sales ?? 0 }}</b> Ventes
                                            </span>
                                            |
                                            <span>
                                                <b>{{ $user->GetPosts->count() }}</b> Annonces
                                            </span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div >
                       
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
                <div class="col-sm-8">
                    @livewire('User.ProfileAnnonces', ['user' => $user])
                </div>
            </div>
        </div>
       
    </div>

@endsection
