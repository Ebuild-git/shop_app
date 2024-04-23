@extends('User.fixe')
@section('titre', $post->titre)
@section('body')





    <!-- ======================= Top Breadcrubms ======================== -->
    <div class="gray py-3">
        <div class="container">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/">Accueil</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">
                                    {{ $post->sous_categorie_info->categorie->titre ?? '' }}
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">
                                    {{ $post->sous_categorie_info->titre }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ $post->titre }}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ======================= Top Breadcrubms ======================== -->

    <!-- ======================= Product Detail ======================== -->
    <section class="middle">
        <div class="container">
            <div class="row">

                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                    <div class="sp-loading">
                        <img src="{{ Storage::url($post->photos[0] ?? '') }}" class="w-100 sp-current-big"
                            style="width: 100% !important;" alt="">
                        <br>LOADING
                        IMAGES
                    </div>
                    <div class="sp-wrap">
                        @forelse ($post->photos as $photo)
                            <a href="{{ Storage::url($photo) }}">
                                <img src="{{ Storage::url($photo) }}" alt="" style="width: 100% !important;">
                            </a>
                        @empty
                        @endforelse
                    </div>
                </div>

                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                    <div class="prd_details">

                        <div class="prt_01 mb-2">
                            @if ($post->sous_categorie_info->categorie->luxury == 1)
                                <span class="text-success bg-light-success rounded px-2 py-1">
                                    <i class="bi bi-gem"></i>
                                    SHOPIN LUXURY
                                </span>
                                &nbsp;
                            @endif
                            <span class="text-info bg-light-info rounded px-2 py-1">
                                {{ $post->sous_categorie_info->categorie->titre ?? '' }}
                            </span>
                            <span class="text-muted">
                                &nbsp;
                            </span>
                            <span class="text-success bg-light-success rounded px-2 py-1 mr-2">
                                {{ $post->sous_categorie_info->titre }}
                            </span>
                            <div class="elis_rty mt-2">
                                @if ($post->old_prix)
                                    <span class="ft-bold color fs-lg">
                                        {{ $post->getPrix() }} DH
                                    </span>
                                    <br>
                                    <strike class="text-danger">
                                        {{ $post->getOldPrix() }} DH
                                    </strike>
                                @else
                                    <span class="ft-bold color fs-lg">
                                        {{ $post->getPrix() }} DH
                                    </span>
                                @endif
                            </div>
                            <div class="prt_02 mb-3">
                                <div class="d-flex justify-content-between">
                                    <h2 class="ft-bold mb-1 mt-2 text-capitalize">
                                        {{ $post->titre }}
                                    </h2>
                                    @auth
                                        @if (Auth::id() != $post->id_user)
                                            <h1 class="h6 text-danger cursor" data-toggle="modal" data-target="#signaler">
                                                <i class="bi bi-exclamation-octagon"></i>
                                                Signaler
                                            </h1>
                                        @endif
                                    @endauth
                                </div>

                                <div class="text-left">
                                    <div class="">
                                        <i class="bi bi-calendar3"></i>
                                        Publier le
                                        {{ Carbon\Carbon::parse($post->created_at)->format('d/m/Y') }} à
                                        {{ Carbon\Carbon::parse($post->created_at)->format('H:i') }}
                                        <br>
                                        Auteur &nbsp;
                                        <a href="/user/{{ $post->user_info->id }}" class="color">
                                            <b>
                                                <i class="bi bi-person-circle"></i>
                                                {{ '@' . $post->user_info->username }}
                                            </b>
                                        </a>
                                    </div>
                                    <br>
                                    <span class="color">
                                        <i class="bi bi-bus-front-fill"></i>
                                        Frais de Livraison : {{ $post->getFraisLivraison() }} DH
                                    </span>
                                </div>
                            </div>

                            <div class="prt_03 mb-4">
                                <p>
                                    {!! $post->description !!}
                                </p>
                            </div>

                            <div class="row">
                                <div class="col-6 mb-1 text-capitalize">
                                    <i class="bi bi-chevron-double-right"></i>
                                    Etat :<strong class="fs-sm text-dark ft-medium ml-1">
                                        {{ $post->etat }}
                                    </strong>
                                </div>
                                <div class="col-6 mb-1 text-capitalize">
                                    <i class="bi bi-chevron-double-right"></i>
                                    Région :<strong class="fs-sm text-dark ft-medium ml-1">
                                        {{ $post->region->nom ?? 'N/A' }}
                                    </strong>
                                </div>
                            </div>
                            <br>
                            <div>

                                <div class="row text-center">
                                    @forelse ($post->proprietes ?? []  as $key => $value)
                                        <div class="col-sm-4 col-4">
                                            <div class="p-2 alert alert-success">
                                                <b>{{ ucfirst($key) }} </b>
                                                <br>
                                                @if ($key = 'couleur' || ($key = 'Couleur'))
                                                    @if ($value == '#000000')
                                                        <div class="multi-color-btn w-100">
                                                            <br><br>
                                                        </div>
                                                    @else
                                                        <span
                                                            style="background-color: {{ $value }} ;color:{{ $value }};"
                                                            class="">
                                                            {{ $value }}
                                                        </span>
                                                    @endif
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                    @endforelse
                                </div>
                            </div>
                            <br>

                            <div class="prt_05 mb-4">
                                <div class="form-row mb-7">
                                    @if ($post->statut == 'vente')
                                        <div class="col-12 col-lg">
                                            <span>
                                                @auth
                                                    <button type="button" class="btn btn-block custom-height bg-dark mb-2 "
                                                        onclick="add_cart({{ $post->id }})">
                                                        <i class="lni lni-shopping-basket mr-2"></i>
                                                        Ajouter au panier
                                                    </button>
                                                @endauth
                                            </span>
                                        </div>
                                    @endif
                                    <div class="col-12 col-lg-auto">
                                        <!-- Wishlist -->
                                        @livewire('User.ButtonAddLike', ['post' => $post])

                                    </div>
                                    @auth
                                        <div class="col-12 col-lg-auto">
                                            @if (Auth::id() != $post->id_user)
                                                @livewire('User.BtnAddFavoris', ['id_post' => $post->id])
                                            @endif
                                        </div>
                                    @endauth
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- ======================= Product Detail End ======================== -->

    <!-- ======================= Product Description ======================= -->
    <section class="middle">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-xl-11 col-lg-12 col-md-12 col-sm-12">
                    <ul class="nav nav-tabs b-0 d-flex align-items-center justify-content-center simple_tab_links mb-4"
                        id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="description-tab" href="#description" data-toggle="tab"
                                role="tab" aria-controls="description" aria-selected="true">Description</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="#information" id="information-tab" data-toggle="tab" role="tab"
                                aria-controls="information" aria-selected="false">Additional information</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">

                        <!-- Description Content -->
                        <div class="tab-pane fade show active" id="description" role="tabpanel"
                            aria-labelledby="description-tab">
                            <div class="description_info">
                                <p class="p-0 mb-2">
                                    {!! $post->description !!}
                                </p>
                            </div>
                        </div>

                        <!-- Additional Content -->
                        <div class="tab-pane fade" id="information" role="tabpanel" aria-labelledby="information-tab">
                            <div class="additionals">
                                <table class="table">
                                    <tbody>
                                        @forelse ($proprietes ?? [] as $key => $value)
                                            <tr>
                                                <th class="ft-medium text-dark">{{ ucfirst($key) }}</th>
                                                <td>{{ $value }}</td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ======================= Product Description End ==================== -->

    <!-- ======================= Similar Products Start ============================ -->
    <section class="middle pt-0">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="sec_title position-relative text-center">
                        <h3 class="ft-bold pt-3">
                            Autres produits de la même catégorie
                        </h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="slide_items">
                        @forelse ($other_products as $other)
                            <!-- single Item -->
                            <div class="single_itesm">
                                <div class="product_grid card b-0 mb-0">
                                    <div
                                        class="badge bg-success-ps text-white position-absolute ft-regular ab-left text-upper">
                                        {{ $other->statut }}
                                    </div>
                                    <button class="snackbar-wishlist btn btn_love position-absolute ab-right"><i
                                            class="far fa-heart"></i></button>
                                    <div class="card-body p-0">
                                        <div class="shop_thumb position-relative">
                                            <a class="card-img-top d-block overflow-hidden"
                                                href="/post/{{ $other->id }}"><img class="card-img-top"
                                                    src="{{ Storage::url($other->photos[0] ?? '') }}" alt="...">
                                                </a>
                                        </div>
                                    </div>
                                    <div class="card-footer b-0 p-3 pb-0 d-flex align-items-start justify-content-center">
                                        <div class="text-left">
                                            <div class="text-center">
                                                <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1">
                                                    <a href="/post/{{ $other->id }}">
                                                        {{ $other->titre }}
                                                    </a>
                                                </h5>
                                                <div class="d-flex justify-content-between">
                                                    <div class="elis_rty color">
                                                        <span class="ft-bold  fs-sm">
                                                            {{ $other->getPrix() }} DH
                                                        </span>
                                                    </div>
                                                    @if ($other->old_prix)
                                                        <div>
                                                            <strike>
                                                                <span class="text-danger">
                                                                    {{ $other->getOldPrix() }} DH
                                                                </span>
                                                            </strike>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- ======================= Similar Products Start ============================ -->




    @auth
        @if (Auth::id() != $post->id_user)
            <!-- Log In Modal -->
            <div class="modal fade" id="signaler" tabindex="1" role="dialog" aria-labelledby="loginmodal"
                aria-hidden="true">
                <div class="modal-dialog modal-xl login-pop-form" role="document">
                    <div class="modal-content" id="loginmodal">
                        <div class="modal-headers">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span class="ti-close"></span>
                            </button>
                        </div>
                        <div class="modal-body p-5">
                            <div class="text-center mb-4">
                                <h1 class="m-0 ft-regular h5 text-danger">
                                    <i class="bi bi-exclamation-octagon"></i>
                                    Signaler une publication.
                                </h1>
                                <span class="text-muted">
                                    " {{ $post->titre }} "
                                </span>
                            </div>
                            @livewire('User.Signalement', ['post' => $post])
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal -->
        @endif
    @endauth


    <style>
        .sp-current-big {
            width: 100% !important;
        }
    </style>
@endsection
