@extends('User.fixe')
@section('titre', 'Accueil')
@section('body')


    <!-- ============================ Hero Banner  Start================================== -->
    <div class="position-relative">
        <div class="carousel">
            @forelse ($categories as $cat)
                <div class="carousel-item text-center" style="background-image: url('{{ Storage::url($cat->icon) }}');">
                    <a href="/shop" class="btn btn-md bg-dark text-light position-absolute "
                        style="font-size: 25px; bottom: 20px;  left: 50%; transform: translateX(-50%);">
                        {{ $cat->titre }}
                    </a>
                </div>
            @empty
            @endforelse
        </div>
        <div class="group-btn-slide">
            <div class="d-flex justify-content-between">
                <button class="btn-slide-home" onclick="prevItem()">
                    <i class="bi bi-arrow-left"></i>
                </button>
                <button class="btn-slide-home" onclick="nextItem()">
                    <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>



    <!-- ============================ Hero Banner End ================================== -->

    <script>
        let currentIndex = 0;
        const items = document.querySelectorAll('.carousel-item');
        const totalItems = items.length;

        let touchstartX = 0;
        let touchendX = 0;

        function showItem(index) {
            items.forEach(item => {
                item.style.display = 'none';
            });
            items[index].style.display = 'block';
        }

        function nextItem() {
            currentIndex = (currentIndex + 1) % totalItems;
            showItem(currentIndex);
        }

        function prevItem() {
            currentIndex = (currentIndex - 1 + totalItems) % totalItems;
            showItem(currentIndex);
        }

        function handleGesture(event) {
            if (touchendX < touchstartX) {
                nextItem();
            }
            if (touchendX > touchstartX) {
                prevItem();
            }
        }

        document.addEventListener('touchstart', function(event) {
            touchstartX = event.changedTouches[0].screenX;
        }, false);

        document.addEventListener('touchend', function(event) {
            touchendX = event.changedTouches[0].screenX;
            handleGesture();
        }, false);

        // Change slide every 3 seconds
        setInterval(nextItem, 6000);
    </script>

    <style>
        .carousel {
            overflow: hidden;
            width: 100%;
            height: 600px;
            /* Ajustez la hauteur selon vos besoins */
        }

        .carousel-item {
            display: none;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
        }

        .carousel-item:first-child {
            display: block;
        }
    </style>


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
                @foreach ($luxurys as $lux)
                    <!-- Single -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                        <div class="product_grid card b-0">

                            @livewire('LikeCard', ['id' => $lux->id])
                            <div class="card-body p-0">
                                <div class="shop_thumb position-relative">
                                    <a class="card-img-top d-block overflow-hidden" href="/post/{{ $lux->id }}">
                                        <img class="card-img-top" src="{{ Storage::url($lux->photos[0] ?? '') }}"
                                            alt="..."></a>
                                </div>
                            </div>
                            <x-SubCardPost :idPost="$lux->id"></x-SubCardPost>
                        </div>
                    </div>
                @endforeach

            </div>
            <!-- row -->
            @if ($luxurys->count() > 0)
                <div class="row justify-content-center">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                        <div class="position-relative text-center">
                            <a href="/shop?luxury_only=true" class="btn stretched-link borders">
                                <span class="voir-plus-home-texte">
                                    Voir Plus
                                </span>
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

                @foreach ($last_post as $last)
                    <!-- Single -->
                    <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                        <div class="product_grid card b-0">
                            @livewire('LikeCard', ['id' => $last->id])
                            <div class="card-body p-0">
                                <div class="shop_thumb position-relative">
                                    <a class="card-img-top d-block overflow-hidden" href="/post/{{ $last->id }}">
                                        <img src="{{ Storage::url($last->photos[0] ?? '') }}" alt="...">
                                    </a>
                                </div>
                            </div>
                            <x-SubCardPost :idPost="$last->id"></x-SubCardPost>
                        </div>
                    </div>
                @endforeach
                <!-- Single -->



            </div>
            <!-- row -->

            <div class="row justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="position-relative text-center">
                        <a href="/shop" class="btn stretched-link borders">
                            <span class="voir-plus-home-texte">
                                Voir Plus
                            </span>
                            <i class="lni lni-arrow-right ml-2"></i></a>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- ======================= Product List ======================== -->

    <br><br>




@endsection
