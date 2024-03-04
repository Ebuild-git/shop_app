@extends('User.fixe')
@section('titre', 'Accueil')
@section('content')
@section('body')

    <br><br>
    <div class="container-fluid">
        @if ($configuration->logo)
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8 mx-auto">
                            {{-- <i class="text-muted small">
                            <i class="bi bi-x-circle"></i> Fermer
                        </i> --}}
                            <img src="{{ Storage::url($configuration->logo) }}" class="w-100" alt="banner"
                                style="border-radius: 10px;">
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <section>
            <div class="d-flex justify-content-between p-2">
                <div>
                    <h4>Récente publications</h4>
                </div>
                <div>
                    <a class="btn bg-red mb-3 mr-1 shadow-none" href="#carouselExampleIndicators2" role="button"
                        data-slide="prev">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <a class="btn bg-red mb-3 shadow-none" href="#carouselExampleIndicators2" role="button"
                        data-slide="next">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            <section>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    @php
                                        $x = 1;
                                    @endphp
                                    @foreach ($posts->chunk(6) as $chunck)
                                        <div class="carousel-item @if ($x == 1) active @endif">
                                            <div class="row">
                                                {{-- max 6 --}}
                                                @foreach ($chunck as $item)
                                                    <x-CardPost :post="$item" :class="'col-12 col-md-2 col-lg-4 col-xl-2'"></x-CardPost>
                                                @endforeach

                                            </div>
                                        </div>
                                        @php
                                            $x++;
                                        @endphp
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>

        <hr>
        <section>
            <div class="d-flex justify-content-between p-2">
                <div>
                    <h4>Toutes les publications</h4>
                </div>
                <div>
                    <a class="btn bg-red mb-3 mr-1 shadow-none" href="#carouselExampleIndicators2" role="button"
                        data-slide="prev">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <a class="btn bg-red mb-3 shadow-none" href="#carouselExampleIndicators2" role="button"
                        data-slide="next">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            <section>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <div class="row">
                                            {{-- max 6 --}}
                                            <div class="col-md-2 mb-3">
                                                <div class="card">
                                                    <img class="img-fluid" alt="100%x280"
                                                        src="https://images.unsplash.com/photo-1532781914607-2031eca2f00d?ixlib=rb-0.3.5&amp;q=80&amp;fm=jpg&amp;crop=entropy&amp;cs=tinysrgb&amp;w=1080&amp;fit=max&amp;ixid=eyJhcHBfaWQiOjMyMDc0fQ&amp;s=7c625ea379640da3ef2e24f20df7ce8d">
                                                    <div class="card-body">
                                                        <span class="text-red">
                                                            <strong>800</strong> Dt
                                                        </span>
                                                        <h6 class="card-title">
                                                            une ferme de 6 hectares à khchana
                                                        </h6>
                                                        <p class="card-text small text-muted">
                                                            <b>
                                                                <i class="bi bi-geo-alt"></i>
                                                            </b> : Tunis <br>
                                                            <b>
                                                                <i class="bi bi-grid-1x2"></i>
                                                            </b> : Electronique, il y'a 3 heures
                                                        </p>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </div>


    <script>
        $('#recipeCarousel').carousel({
            interval: 10000
        })

        $('.carousel .carousel-item').each(function() {
            var minPerSlide = 6;
            var next = $(this).next();
            if (!next.length) {
                next = $(this).siblings(':first');
            }
            next.children(':first-child').clone().appendTo($(this));

            for (var i = 0; i < minPerSlide; i++) {
                next = next.next();
                if (!next.length) {
                    next = $(this).siblings(':first');
                }

                next.children(':first-child').clone().appendTo($(this));
            }
        });
    </script>
    <style>
        @media (max-width: 768px) {
            .carousel-inner .carousel-item>div {
                display: none;
            }

            .carousel-inner .carousel-item>div:first-child {
                display: block;
            }
        }

        .carousel-inner .carousel-item.active,
        .carousel-inner .carousel-item-next,
        .carousel-inner .carousel-item-prev {
            display: flex;
        }

        /* display 3 */
        @media (min-width: 768px) {

            .carousel-inner .carousel-item-right.active,
            .carousel-inner .carousel-item-next {
                transform: translateX(33.333%);
            }

            .carousel-inner .carousel-item-left.active,
            .carousel-inner .carousel-item-prev {
                transform: translateX(-33.333%);
            }
        }

        .carousel-inner .carousel-item-right,
        .carousel-inner .carousel-item-left {
            transform: translateX(0);
        }
    </style>

@endsection
