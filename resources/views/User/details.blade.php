@extends('User.fixe')
@section('titre', $post->titre)
@section('body')


    @php
        $photos = json_decode($post->photos, true);
        $proprietes = json_decode($post->proprietes, true);
    @endphp


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
                    <div class="sp-loading"><img src="{{ Storage::url($photos[0] ?? '') }}" alt=""><br>LOADING
                        IMAGES</div>
                    <div class="sp-wrap">
                        @forelse ($photos as $photo)
                            <a href="{{ Storage::url($photo) }}">
                                <img src="{{ Storage::url($photo) }}" alt="">
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
                            <span class="text-success bg-light-success rounded px-2 py-1 mr-2">
                                {{ $post->sous_categorie_info->titre }}
                            </span>
                            <span class="text-muted">

                            </span>
                            <span class="text-info bg-light-info rounded px-2 py-1">
                                {{ $post->sous_categorie_info->categorie->titre ?? '' }}
                            </span>
                        </div>
                        <div class="prt_02 mb-3">
                            <h2 class="ft-bold mb-1">
                                {{ $post->titre }}
                            </h2>
                            <div class="text-left">
                                <div class="star-rating align-items-center d-flex justify-content-left mb-1 p-0">
                                    <i class="bi bi-calendar3"></i>
                                    Publier le
                                    {{ Carbon\Carbon::parse($post->created_at)->format('d/m/Y') }} à
                                    {{ Carbon\Carbon::parse($post->created_at)->format('H:i') }}
                                    par &nbsp;
                                    <a href="#" class="color">
                                        <b>
                                            <i class="bi bi-person-circle"></i>
                                            {{ $post->user_info->username }}
                                        </b>
                                    </a>
                                </div>
                                <div class="elis_rty">
                                    <span class="ft-bold color fs-lg">
                                        @livewire('User.prix', ['id_post' => $post->id]) DH
                                    </span>
                                </div>
                                <span class="color">
                                    <i class="bi bi-bus-front-fill"></i>
                                    Frais de Livraison : {{ $post->sous_categorie_info->categorie->frais_livraison ?? 0 }}
                                    DH
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
                                Gouvernorat :<strong class="fs-sm text-dark ft-medium ml-1">
                                    {{ $post->gouvernorat }}
                                </strong>
                            </div>
                        </div>
                        <br>
                        <div>

                            <div class="row text-center">
                                @forelse ($proprietes  as $key => $value)
                                    <div class="col-sm-4 col-4">
                                        <div class="p-2 alert alert-success">
                                            <b>{{ ucfirst($key) }} </b>
                                            <br>
                                            {{ $value }}
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                        </div>
                        <br>

                        <div class="prt_05 mb-4">
                            <div class="form-row mb-7">
                                <div class="col-12 col-lg">
                                    <!-- Submit -->
                                    @livewire('User.ButtonAddPanier', ['id_post' => $post->id])
                                </div>
                                <div class="col-12 col-lg-auto">
                                    <!-- Wishlist -->
                                    @auth
                                        @livewire('User.ButtonAddLike', ['id_post' => $post->id])
                                    @endauth

                                </div>
                            </div>
                        </div>

                        <div class="prt_06">
                            <p class="mb-0 d-flex align-items-center">
                                <span class="mr-4">Share:</span>
                                <a class="d-inline-flex align-items-center justify-content-center p-3 gray circle fs-sm text-muted mr-2"
                                    href="#!">
                                    <i class="fab fa-twitter position-absolute"></i>
                                </a>
                                <a class="d-inline-flex align-items-center justify-content-center p-3 gray circle fs-sm text-muted mr-2"
                                    href="#!">
                                    <i class="fab fa-facebook-f position-absolute"></i>
                                </a>
                                <a class="d-inline-flex align-items-center justify-content-center p-3 gray circle fs-sm text-muted"
                                    href="#!">
                                    <i class="fab fa-pinterest-p position-absolute"></i>
                                </a>
                            </p>
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
                                        @forelse ($proprietes  as $key => $value)
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
                            @php
                                $photo = json_decode($other->photos, true);
                            @endphp
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
                                                    src="{{ Storage::url($photo[0] ?? '') }}" alt="..."></a>
                                            <div
                                                class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                                <div class="edlio"><a href="#" data-toggle="modal"
                                                        data-target="#quickview" class="text-white fs-sm ft-medium"><i
                                                            class="fas fa-eye mr-1"></i>Quick View</a></div>
                                            </div>
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
                                                <div class="elis_rty">
                                                    <span class="ft-bold fs-md color">
                                                        @livewire('User.prix', ['id_post' => $other->id]) DH
                                                    </span>
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

@endsection
