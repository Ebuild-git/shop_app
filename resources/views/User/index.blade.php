@extends('User.fixe')
@section('titre', 'Accueil')
@section('body')


    <!-- ============================ Hero Banner  Start================================== -->
    <div class="home-slider margin-bottom-0">


        @forelse ($categories as $cat)
            <!-- Slide -->
            <div data-background-image="{{ Storage::url($cat->icon) }}" class="item ">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="home-slider-container ">

                                <!-- Slide Title -->
                                <div class="home-slider-desc text-center p-2 position-absolute header-btn-position">
                                    <div class="home-slider-title mb-4">
                                    </div>
                                    <a href="/shop" class="btn btn-md  bg-dark text-light  " style="font-size: 25px;">
                                        {{ $cat->titre }}
                                    </a>
                                </div>
                                <!-- Slide Title / End -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
        @endforelse
    </div>

    <script>
        $(document).ready(function() {
            $('.slick-next').on('click', function(event) {
                // Arrête la propagation de l'événement de clic
                event.stopPropagation();

                // Clique sur le bouton slick-next
                $(this).click();
            });

            // Auto clic sur le bouton slick-next toutes les 3 secondes
            setInterval(function() {
                $('.slick-next').click();
            }, 6000);
        });
    </script>
    <!-- ============================ Hero Banner End ================================== -->

    <!-- ======================= Product List ======================== -->
    <section class="middle">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="sec_title position-relative text-center">
                        <h3 class="ft-bold pt-3">Nouveau Sur SHOP<span class="color">IN</span> LUXURY</h3>
                    </div>
                </div>
            </div>

            <!-- row -->
            <div class="row align-items-center rows-products">
                @forelse ($luxurys as $lux)
                    <!-- Single -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                        <div class="product_grid card b-0">
                            <div class="badge bg-success-ps text-white position-absolute ft-regular ab-left text-upper">
                                <i class="bi bi-gem"></i> LUXURY
                            </div>
                            <div class="badge badge-like-post-count position-absolute ab-right text-upper">
                                <i class="far fa-heart"></i>
                                <span>
                                    {{ $lux->getLike->count() }}
                                </span>
                            </div>
                            <div class="card-body p-0">
                                <div class="shop_thumb position-relative">
                                    <a class="card-img-top d-block overflow-hidden" href="/post/{{ $lux->id }}"><img
                                            class="card-img-top" src="{{ Storage::url($lux->photos[0] ?? '') }}"
                                            alt="..."></a>
                                    <div
                                        class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                        <div class="edlio"><a href="#" data-toggle="modal"
                                                data-target="#quickview-{{ $lux->id }}"
                                                class="text-white fs-sm ft-medium">
                                                <i class="fas fa-eye mr-1"></i>
                                                vue rapide </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer b-0 p-0 pt-2 bg-white">
                                <div class="">
                                    <div class="text-left">
                                        {{ $lux->sous_categorie_info->titre }}
                                    </div>
                                </div>
                                <div class="text-left">
                                    <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1">
                                        <a href="/post/{{ $lux->id }}">
                                            {{ $lux->titre }}
                                        </a>
                                    </h5>
                                    <div class="elis_rty color">
                                        <span class="ft-bold  fs-sm">
                                            {{ $lux->getPrix() }}DH
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @livewire('User.ProductViewModal', ['id_post' => $lux->id])
                @empty
                @endforelse

            </div>
            <!-- row -->
            @if ($luxurys->count() > 0)
                <div class="row justify-content-center">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                        <div class="position-relative text-center">
                            <a href="/shop?categorie={{ $luxurys->first()->id_categorie }}"
                                class="btn stretched-link borders">
                                Voir Plus
                                <i class="lni lni-arrow-right ml-2"></i></a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- ======================= Product List ======================== -->


    <!-- ======================= Product List ======================== -->
    <section class="middle">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="sec_title position-relative text-center">
                        <h3 class="ft-bold pt-3">Nouveau Sur SHOP<span class="color">IN</span> </h3>
                    </div>
                </div>
            </div>

            <!-- row -->
            <div class="row align-items-center rows-products">

                @forelse ($last_post as $last)
                    <!-- Single -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                        <div class="product_grid card b-0">
                            <div class="badge bg-success-ps text-white position-absolute ft-regular ab-left text-upper">
                                {{ $last->statut }}
                            </div>
                            <div class="badge badge-like-post-count position-absolute ab-right text-upper">
                                <i class="far fa-heart"></i>
                                <span>
                                    {{ $last->getLike->count() }}
                                </span>
                            </div>
                            <div class="card-body p-0">
                                <div class="shop_thumb position-relative">
                                    <a class="card-img-top d-block overflow-hidden" href="/post/{{ $last->id }}">
                                        <img src="{{ Storage::url($last->photos[0] ?? '') }}" alt="...">
                                    </a>
                                    <div
                                        class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                        <div class="edlio">
                                            <a href="#" data-toggle="modal"
                                                data-target="#quickview-{{ $last->id }}"
                                                class="text-white fs-sm ft-medium">
                                                <i class="fas fa-eye mr-1"></i>
                                                Vue rapide
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer b-0 p-0 pt-2 bg-white">
                                <div class="">
                                    <div class="text-left">
                                        {{ $last->sous_categorie_info->titre }}
                                    </div>
                                </div>
                                <div class="text-left">
                                    <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1">
                                        <a href="/post/{{ $last->id }}">
                                            {{ $last->titre }}
                                        </a>
                                    </h5>
                                    <div class="elis_rty color">
                                        <span class="ft-bold  fs-sm">
                                            {{ $last->getPrix() }}DH
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @livewire('User.ProductViewModal', ['id_post' => $last->id])
                @empty
                @endforelse
                <!-- Single -->



            </div>
            <!-- row -->

            <div class="row justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="position-relative text-center">
                        <a href="/shop" class="btn stretched-link borders">
                            Voir Plus
                            <i class="lni lni-arrow-right ml-2"></i></a>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- ======================= Product List ======================== -->

    <br><br>




@endsection
