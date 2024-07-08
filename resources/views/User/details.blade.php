@extends('User.fixe')
@section('titre', $post->titre)
@section('body')


    <style>
        figure.zoom {
            background-position: 50% 50%;
            position: relative;
            overflow: hidden;
            cursor: zoom-in;
        }

        figure.zoom img:hover {
            opacity: 0;
            min-width: 100%;
        }

        figure.zoom img {
            transition: opacity .5s;
            display: block;
            width: 100%;
        }

        .zoom-up-details {
            position: absolute;
            right: 10px;
            top: 10px;
            background-color: rgba(255, 255, 255, 0.616);
            padding: 5px;
            border-radius: 5px;
            cursor: pointer !important;
            box-shadow: 0px 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .zoom-up-details img {
            width: 30px !important;
        }

        .modal-view-img {
            max-height: 80vh !important;
            height: auto !important;
        }
    </style>
    <script>
        function zoom(e) {
            var zoomer = e.currentTarget;
            e.offsetX ? offsetX = e.offsetX : offsetX = e.touches[0].pageX
            e.offsetY ? offsetY = e.offsetY : offsetX = e.touches[0].pageX
            x = offsetX / zoomer.offsetWidth * 100
            y = offsetY / zoomer.offsetHeight * 100
            zoomer.style.backgroundPosition = x + '% ' + y + '%';
        }

        function change_principal_image(url) {
            document.getElementById("imgPrincipale").src = url;
            document.getElementById("figure").style.backgroundImage = "url('" + url + "')";
            //change data-url attibut value on figure
            document.getElementById("figure").setAttribute("data-url", url);
        }
    </script>



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
                                <a href="{{ route('shop') }}?id_categorie={{ $post->sous_categorie_info->categorie->id ?? '' }}">
                                    {{ $post->sous_categorie_info->categorie->titre ?? '' }}
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">
                                   <b class="color"> {{ $post->sous_categorie_info->titre }}</b>
                                </a>
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
                <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12">
                    <div>
                        <div class="row">
                            <div class="col-2 p-1">
                                @foreach ($post->photos as $photo)
                                    <div class="gallery-image-details cusor"
                                        onclick="change_principal_image('{{ Storage::url($photo) }}')">
                                        <img src="{{ Storage::url($photo) }}" alt=""
                                            style="width: 100% !important;">
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-10 p-1 ">
                                <figure class="zoom w-100 position-relative " id="figure" onmousemove="zoom(event)"
                                    data-url="{{ Storage::url($post->photos[0] ?? '') }}"
                                    style="background-image: url({{ Storage::url($post->photos[0] ?? '') }})">
                                    <button type="button"
                                        class="btn-like-details btn-like-post @if ($isLiked) btn-favoris-added @endif"
                                        @guest data-toggle="modal" data-target="#login" @endguest
                                        data-id="{{ $post->id }}">
                                        <div class="d-flex justify-content-between">
                                            <span class="my-auto count">
                                                {{ $post->getLike->count() }}
                                            </span>
                                            <span class="my-auto">
                                                <i class="bi bi-heart"></i>
                                            </span>
                                        </div>
                                    </button>

                                    <span class="zoom-up-details">
                                        <img src="/icons/icons8-dézoomer-58.png" alt="" srcset="">
                                    </span>
                                    <img src="{{ Storage::url($post->photos[0] ?? '') }}" id="imgPrincipale"
                                        class="w-100 sp-current-big" alt="image">
                                </figure>
                            </div>
                        </div>
                    </div>



                    <hr>
                    <h5>
                        <b>Voilà le SHOP<span class="color">IN</span>ER</b>
                    </h5>
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

                                        <x-Etoiles :count="$count" :avis="$avis"></x-Etoiles>

                                        <div>
                                            <span>
                                                <b> {{ $avis }} </b> avis
                                            </span>
                                            |
                                            <span>
                                                <b>{{ $user->total_sales->count() }}</b> Ventes
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
                    <br><br>
                </div>

                <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12">
                    <div class="prd_details">

                        <div class="prt_01 mb-2">
                            @if ($post->sous_categorie_info->categorie->luxury == 1)
                                <span class="text-success bg-light-success rounded strong px-2 py-1">
                                    <i class="bi bi-gem"></i>
                                    SHOPIN LUXURY
                                </span>
                                &nbsp;
                            @endif
                            <span class=" bg-light-info rounded color px-2 py-1 strong" style="background-color: #0080802d">
                                {{ $post->sous_categorie_info->categorie->titre ?? '' }}
                            </span>
                            <span class="text-muted">
                                &nbsp;
                            </span>
                            <span class=" rounded px-2 py-1 mr-2 strong" style="background-color: #ecedf1">
                                {{ $post->sous_categorie_info->titre }}
                            </span>
                            <div class="prt_02 mb-5 mt-3">
                                <h2 class=" mb-1 mt-2 text-capitalize">
                                    {{ $post->titre }}
                                </h2>

                                <div class="text-left">
                                    <div class="elis_rty mt-2">
                                        @if ($post->changements_prix->count())
                                            <span class="strong color fs-lg">
                                                <strike>
                                                    {{ $post->getOldPrix() }}
                                                </strike> DH
                                            </span>
                                            <br>
                                            <span class="text-danger h5 strong">
                                                {{ $post->getPrix() }} DH
                                            </span>
                                        @else
                                            <span class="ft-bold color strong fs-lg">
                                                {{ $post->getPrix() }} DH
                                            </span>
                                        @endif

                                        <span class="badge-frais-details">
                                            <img width="25" height="25"
                                                src="https://img.icons8.com/laces/25/018d8d/delivery.png" alt="delivery" />
                                            + Frais de Livraison
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <x-BtnAddPanier :post="$post"></x-BtnAddPanier>

                            @auth
                                @if (Auth::id() == $post->id_user)
                                    <button type="button" onclick="Update_post_price({{ $post->id }})"
                                        class="btn btn-default btn-block mb-2" type="button">
                                        <i class="bi bi-pencil-square"></i>
                                        Réduire le prix
                                    </button>
                                @endif
                                @if (Auth::id() != $post->id_user)
                                    <button
                                        class="btn btn-default btn-block btn-add-favoris @if ($isFavorited) btn-favoris-added @endif "
                                        type="button" @guest data-toggle="modal" data-target="#login" @endguest
                                        data-id="{{ $post->id }}">
                                        <i class="lni lni-heart mr-2"></i>
                                        <span class="text">
                                            {{ $isFavorited ? 'Retirer des favoris' : 'Ajouter aux favoris' }}
                                        </span>
                                    </button>
                                    <br>
                                    <div class="text-center">
                                        <span class=" text-danger cursor" data-toggle="modal" data-target="#signaler">
                                            <i class="bi bi-exclamation-octagon"></i>
                                            Signaler cette annonce
                                        </span>
                                        <br><br>
                                    </div>
                                <div>
                                    <p class="text-center pr-5 pl-5">
                                        En poursuivant votre commande, vous acceptez les
                                        <a href="{{ route('conditions') }}" class="color">
                                            <b>Conditions générales</b>
                                        </a> de SHOPIN
                                    </p>
                                </div>
                                @endif
                            @endauth
                            <hr>
                            <div class="prt_03 mb-4">
                                <b class="text-black h5">Détails</b>
                                <p>
                                <table>
                                    <tr>
                                        <td style="min-width: 130px">Condition </td>
                                        <td class="text-black"> {{ $post->etat }} </td>
                                    </tr>
                                    <tr>
                                        <td>Catégorie </td>
                                        <td class="text-black">
                                            {{ $post->sous_categorie_info->categorie->titre ?? '' }}
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
                                            <td>{{ ucfirst($key) }} </td>
                                            <td class="text-black">
                                                @if ($key == 'Couleur')
                                                    @if ($value == '#000000000')
                                                        <img src="/icons/color-wheel.png" height="20" width="20"
                                                            alt="multicolor" title="Multi color" srcset="">
                                                    @else
                                                        <script>
                                                            getColorName('{{ $value }}');
                                                        </script>
                                                        <span class="card"
                                                            style="background-color: {{ $value }} ;color:{{ $value }};">
                                                            {{ $value }}
                                                        </span>
                                                    @endif
                                                    <span id="colorName"></span>
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </table>
                                </p>
                                <b class="text-black h5">Description</b>
                                <p>
                                    @if ($post->description)
                                        {!! $post->description !!}
                                    @else
                                        <div class="text-muted text-center">
                                            <i>
                                                <i class="bi bi-info-circle color"></i>
                                                Aucune description disponible !
                                            </i>
                                        </div>
                                    @endif

                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- ======================= Product Detail End ======================== -->



    <!-- ======================= Similar Products Start ============================ -->
    <section class="middle pt-0">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="sec_title position-relative ">
                        <h3 class="ft-bold pt-3">
                            Articles similaires
                        </h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="slide_items">
                        @foreach ($other_products as $other)
                            <!-- single Item -->
                            <div class="single_itesm">
                                <div class="product_grid card b-0 mb-0">
                                    @livewire('LikeCard', ['id' => $other->id])
                                    <div class="card-body p-0">
                                        <div class="shop_thumb position-relative">
                                            <a class="card-img-top d-block overflow-hidden"
                                                href="/post/{{ $other->id }}"><img class="card-img-top"
                                                    src="{{ Storage::url($other->photos[0] ?? '') }}" alt="...">
                                            </a>
                                        </div>
                                    </div>
                                    <x-SubCardPost :idPost="$other->id"></x-SubCardPost>
                                </div>
                            </div>
                        @endforeach
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
                                    Signaler l'annonce.
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


    <!-- Modal view-->
    <div class="modal fade" id="Modal-view" tabindex="1" role="dialog" aria-labelledby="loginmodal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg login-pop-form text-center" role="document">
            <img src="" id="modal-view-image" alt="image" class="zoom-in modal-view-img">
        </div>
    </div>
    <!-- End Modal -->

    <style>
        .sp-current-big {
            width: 100% !important;
        }
    </style>
    <script>
        $(document).ready(function() {
            $("#imgPrincipale").on('click', function() {
                //get url in src on this
                var url = $(this).attr("src");
                //add image on modal-view
                $('#modal-view-image').attr("src", url);
                //open modal
                $('#Modal-view').modal("show");
            });
        });
    </script>

@endsection
