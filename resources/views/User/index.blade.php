@extends('User.fixe')
@section('titre', 'Accueil')
@section('content')
@section('body')

    <br><br>
    <x-RechercheHeaderNav></x-RechercheHeaderNav>
    <div class="container">
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
                    <span class="h5">
                        Récente publications
                    </span>
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
                                                    <x-CardPost :post="$item" :class="'col-12 col-md-2 col-lg-4 col-xl-3'"></x-CardPost>
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
        <br>
        <hr>
        <section>
            <div class="h5">
                Rechercher par marque
            </div>
            <br>
            <div class="row">
                @foreach ($categories as $item)
                    <div class="col-sm-2 text-center">

                        <div class="border p-2">
                            <span class="position-absolute h4 card">
                                +{{ $item->getPost->count() }}
                            </span>
                            <div>
                                <img src="{{ Storage::url($item->icon) }}" alt="" style="width: 80%">
                            </div>
                            <span>
                                {{ $item->titre }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

    </div>

    <br><br>
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
