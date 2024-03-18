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
        //auto click every 5 seconde on slick-next button class
        $(document).ready(function() {
            setInterval(function() {
                $('.slick-next').click();
            }, 3000);
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
                    @php
                        $photo = json_decode($lux->photos, true);
                    @endphp
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
                                            class="card-img-top" src="{{ Storage::url($photo[0] ?? '') }}"
                                            alt="..."></a>
                                    <div
                                        class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                        <div class="edlio"><a href="#" data-toggle="modal" data-target="#quickview"
                                                class="text-white fs-sm ft-medium"><i class="fas fa-eye mr-1"></i>Quick
                                                View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer b-0 p-0 pt-2 bg-white">
                                <div class="">
                                    <div class="text-left">
                                        {{ $lux->sous_categorie_info->titre }}
                                    </div>
                                    <div class="text-left">
                                        Marque if available {{ $lux->id }}
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
                                            @livewire('User.prix', ['id_post' => $lux->id])
                                            DH
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                    @php
                        $photo = json_decode($last->photos, true);
                    @endphp
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
                                        <img src="{{ Storage::url($photo[0] ?? '') }}" alt="...">
                                    </a>
                                    <div
                                        class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                        <div class="edlio">
                                            <a href="#" data-toggle="modal" data-target="#quickview"
                                                class="text-white fs-sm ft-medium"><i class="fas fa-eye mr-1"></i>Quick
                                                View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer b-0 p-0 pt-2 bg-white">
                                <div class="">
                                    <div class="text-left">
                                        {{ $last->sous_categorie_info->titre }}
                                    </div>
                                    <div class="text-left">
                                        Marque if available
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
                                            @livewire('User.prix', ['id_post' => $last->id])
                                            DH
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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

    <!-- ======================= Customer Review ======================== -->
    <section class="gray">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="sec_title position-relative text-center">
                        <h2 class="off_title">Testimonials</h2>
                        <h3 class="ft-bold pt-3">Client Reviews</h3>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-xl-9 col-lg-10 col-md-12 col-sm-12">
                    <div class="reviews-slide px-3">

                        <!-- single review -->
                        <div class="single_review">
                            <div class="sng_rev_thumb">
                                <figure><img src="https://via.placeholder.com/500x500" class="img-fluid circle"
                                        alt="" /></figure>
                            </div>
                            <div class="sng_rev_caption text-center">
                                <div class="rev_desc mb-4">
                                    <p class="fs-md">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                        eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.</p>
                                </div>
                                <div class="rev_author">
                                    <h4 class="mb-0">Mark Jevenue</h4>
                                    <span class="fs-sm">CEO of Addle</span>
                                </div>
                            </div>
                        </div>

                        <!-- single review -->
                        <div class="single_review">
                            <div class="sng_rev_thumb">
                                <figure><img src="https://via.placeholder.com/500x500" class="img-fluid circle"
                                        alt="" /></figure>
                            </div>
                            <div class="sng_rev_caption text-center">
                                <div class="rev_desc mb-4">
                                    <p class="fs-md">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                        eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.</p>
                                </div>
                                <div class="rev_author">
                                    <h4 class="mb-0">Henna Bajaj</h4>
                                    <span class="fs-sm">Aqua Founder</span>
                                </div>
                            </div>
                        </div>

                        <!-- single review -->
                        <div class="single_review">
                            <div class="sng_rev_thumb">
                                <figure><img src="https://via.placeholder.com/500x500" class="img-fluid circle"
                                        alt="" /></figure>
                            </div>
                            <div class="sng_rev_caption text-center">
                                <div class="rev_desc mb-4">
                                    <p class="fs-md">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                        eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.</p>
                                </div>
                                <div class="rev_author">
                                    <h4 class="mb-0">John Cenna</h4>
                                    <span class="fs-sm">CEO of Plike</span>
                                </div>
                            </div>
                        </div>

                        <!-- single review -->
                        <div class="single_review">
                            <div class="sng_rev_thumb">
                                <figure><img src="https://via.placeholder.com/500x500" class="img-fluid circle"
                                        alt="" /></figure>
                            </div>
                            <div class="sng_rev_caption text-center">
                                <div class="rev_desc mb-4">
                                    <p class="fs-md">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                        eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.</p>
                                </div>
                                <div class="rev_author">
                                    <h4 class="mb-0">Madhu Sharma</h4>
                                    <span class="fs-sm">Team Manager</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ======================= Customer Review ======================== -->
    <br><br>




@endsection
