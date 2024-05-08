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
                    <button type="button" class="expand-collapse-arrows ">
                        <img width="20" height="20"
                            src="https://img.icons8.com/pastel-glyph/64/1A1A1A/expand-collapse-arrows.png"
                            alt="expand-collapse-arrows" />
                    </button>
                    @livewire('User.ButtonAddLike', ['post' => $post])
                    <div class="sp-loading">
                        <img src="{{ Storage::url($post->photos[0] ?? '') }}" class="w-100 sp-current-big"
                            style="width: 100% !important;" alt="">
                        <br>LOADING
                        IMAGES
                    </div>
                    <div class="sp-wrap" id="sp-loading--s">
                        @forelse ($post->photos as $photo)
                            <a href="{{ Storage::url($photo) }}">
                                <img src="{{ Storage::url($photo) }}" alt="" style="width: 100% !important;">
                            </a>
                        @empty
                        @endforelse
                    </div>
                    <hr>
                    <h5>
                        <b>Voilà le SHOP<span class="color">IN</span>ER!</b>
                    </h5>
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
                            <span class="text-success bg-light-info rounded px-2 py-1">
                                {{ $post->sous_categorie_info->categorie->titre ?? '' }}
                            </span>
                            <span class="text-muted">
                                &nbsp;
                            </span>
                            <span class=" rounded px-2 py-1 mr-2" style="background-color: #ecedf1">
                                {{ $post->sous_categorie_info->titre }}
                            </span>
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
                                    <br>
                                    <span style="background-color: #ecedf1;border-bottom:solid 2px #008080; "
                                        class="p-2">
                                        <b>
                                            + {{ $post->getFraisLivraison() }} DH de Frais de Livraison
                                        </b>
                                    </span>
                                </div>
                            </div>
                            <br>
                            <hr>
                            <div class="prt_03 mb-4">
                                <b class="text-black">Détails</b>
                                <p>
                                <table>
                                    <tr>
                                        <td style="min-width: 100px">Condition </td>
                                        <td class="text-black"> {{ $post->etat }} </td>
                                    </tr>
                                    <tr>
                                        <td>Catégorie </td>
                                        <td class="text-black"> {{ $post->sous_categorie_info->categorie->titre ?? '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Région </td>
                                        <td class="text-black"> {{ $post->region->nom ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Publié le </td>
                                        <td class="text-black">
                                            {{ Carbon\Carbon::parse($post->created_at)->format('d/m/Y') }} </td>
                                    </tr>
                                    @forelse ($post->proprietes ?? []  as $key => $value)
                                        <tr>
                                            <td class="text-black">{{ ucfirst($key) }} </td>
                                            <td>
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
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </table>
                                </p>
                                <b class="text-black">Description</b>
                                <p>
                                    {!! $post->description !!}
                                </p>
                                <br>
                                <b class="text-black">Frais de livraison</b>
                                <p class="text-black">
                                    {{ $post->getFraisLivraison() }} DH
                                </p>
                            </div>

                            @auth
                                @if ($post->id_user != Auth::id())
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
                                            @auth
                                                <div class="col-12 col-lg-auto">
                                                    @if (Auth::id() != $post->id_user)
                                                        @livewire('User.BtnAddFavoris', ['id_post' => $post->id])
                                                    @endif
                                                </div>
                                            @endauth
                                        </div>
                                    </div>
                                @endif
                            @endauth

                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- ======================= Product Detail End ======================== -->

    <div class="container">
        <hr>
    </div>

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
                                    @livewire('LikeCard', ['id' => $other->id])
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
    <script>
        $(document).ready(function() {
            $("#sp-loading--s").on('click', function() {
                $("#close-modal-preview").show();
            });
        });
    </script>

@endsection
