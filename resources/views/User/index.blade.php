@extends('User.fixe')
@section('titre', 'Accueil')
@section('body')


    <!-- ============================ Hero Banner  Start================================== -->
    <div class="position-relative">
        <div class="carousel">
            @forelse ($categories_carousel as $cat)
                <div class="carousel-item text-center" style="background-image: url('{{ Storage::url($cat->icon) }}');">
                    <a href="{{ route('shop', ['id_categorie' => $cat->id]) }}" class="carousel-btn">
                        {{ \App\Traits\TranslateTrait::TranslateText($cat->titre) }}
                        @if ($cat->luxury)
                            <i class="bi bi-gem gem-icon"></i>
                        @endif
                    </a>
                </div>
            @empty
            @endforelse

        </div>
        <div class="group-btn-slide">
                <button class="btn-slide-home" onclick="prevItem()">
                    <i class="bi bi-arrow-left"></i>
                </button>
                <button class="btn-slide-home" onclick="nextItem()">
                    <i class="bi bi-arrow-right"></i>
                </button>
        </div>
    </div>



    <!-- ============================ Hero Banner End ================================== -->


    <!-- ======================= Product List ======================== -->

    <section class="middle">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="sec_title position-relative text-center">
                        <h3 class="ft-bold pt-3">{!! __('nouveau_sur_shopin_luxury') !!} {!! __('luxury2') !!}</h3>
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                @foreach ($luxurys as $lux)
                    <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                        <div class="product_grid card b-0">
                            <div class="card-body p-0">
                                <div class="shop_thumb position-relative">

                                    <div class="badge-container position-absolute top-0 start-0 d-flex gap-4 mobile-display-luxe" style="z-index: 5;">
                                        @if($lux->discountPercentage)
                                            <div class="badge-new badge-discount">
                                                -{{ $lux->discountPercentage }}%
                                            </div>
                                        @endif

                                        @if($lux->statut === 'vendu')
                                            <div class="badge-new badge-sale bg-danger text-white">
                                                {{ \App\Traits\TranslateTrait::TranslateText('Vendu') }}
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Like Button -->
                                    <button type="button" class="badge badge-like-post-count btn-like-post position-absolute ab-right cusor"
                                            id="post-{{ $lux->id }}" data-post-id="{{ $lux->id }}"
                                            onclick="btn_like_post({{ $lux->id }})">
                                        <i class="bi bi-suit-heart-fill"></i>
                                        <span class="count">{{ $lux->getLike->count() }}</span>
                                    </button>

                                    <!-- Product Image -->
                                    <a class="card-img-top d-block overflow-hidden" href="/post/{{ $lux->id }}">
                                        <img src="{{ Storage::url($lux->photos[0] ?? '') }}" alt="...">
                                    </a>
                                </div>
                            </div>
                            <!-- SubCardPost Component -->
                            <x-SubCardPost :idPost="$lux->id"></x-SubCardPost>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($luxurys->count() > 0)
                <div class="row justify-content-center">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                        <div class="position-relative text-center">
                            <a href="/shop?luxury_only=true" class="btn stretched-link borders">
                                <span class="voir-plus-home-texte">
                                    {{ \App\Traits\TranslateTrait::TranslateText('Voir Plus') }}
                                </span>
                                <i class="lni lni-arrow-right ml-2"></i></a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>


    <section class="middle">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="sec_title position-relative text-center">
                        <h3 class="ft-bold pt-3">{!! __('nouveau_sur_shopin_luxury') !!}</h3>
                    </div>
                </div>
            </div>

            <div class="row align-items-center">
                @foreach ($last_post as $last)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                        <div class="product_grid card b-0">
                            <!-- Like Button -->
                            <button type="button" class="badge badge-like-post-count btn-like-post position-absolute ab-right cusor"
                                    id="post-{{ $last->id }}" data-post-id="{{ $last->id }}"
                                    onclick="btn_like_post({{ $last->id }})">
                                <i class="bi bi-suit-heart-fill"></i>
                                <span class="count">{{ $last->getLike->count() }}</span>
                            </button>

                            <div class="card-body p-0">
                                <div class="shop_thumb position-relative">
                                    <!-- Discount Badge -->

                                    <div class="badge-container position-absolute top-0 start-0 d-flex gap-4 mobile-display" style="z-index: 5;">
                                        @if($last->discountPercentage)
                                            <div class="badge-new badge-discount">
                                                -{{ $last->discountPercentage }}%
                                            </div>
                                        @endif

                                        @if($last->statut === 'vendu')
                                            <div class="badge-new badge-sale bg-danger text-white">
                                                {{ \App\Traits\TranslateTrait::TranslateText('Vendu') }}
                                            </div>
                                        @endif
                                    </div>


                                    <!-- Product Image -->
                                    <a class="card-img-top d-block overflow-hidden" href="/post/{{ $last->id }}">
                                        <img src="{{ Storage::url($last->photos[0] ?? '') }}" alt="...">
                                    </a>
                                </div>
                            </div>
                            <!-- SubCardPost Component -->
                            <x-SubCardPost :idPost="$last->id"></x-SubCardPost>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- row -->
            <div class="row justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="position-relative text-center">
                        <a href="/shop" class="btn stretched-link borders">
                            <span class="voir-plus-home-texte">
                                {{ \App\Traits\TranslateTrait::TranslateText('Voir Plus') }}
                            </span>
                            <i class="lni lni-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ======================= End Product List ======================== -->

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
        setInterval(nextItem, 6000);
    </script>







@endsection
