<section class="middle">
    <div class="container">
        <div class="row">

            <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 p-xl-0">
                <div class="search-sidebar sm-sidebar border">
                    <div class="search-sidebar-body">
                        <form wire:submit="filtrer">
                            <div>
                                <input type="text" class="form-control" wire:model.live="key" placeholder="Mot clé">
                            </div>

                            <!-- Single Option -->
                            <div class="single_search_boxed">
                                <div class="widget-boxed-header px-3">
                                    <h4 class="mt-3">Categories</h4>
                                </div>
                                <div class="widget-boxed-body">
                                    <div class="side-list no-border">
                                        <div class="filter-card" id="shop-categories">

                                            @forelse ($liste_categories as $categorie)
                                                <!-- Single Filter Card -->
                                                <div class="single_filter_card">
                                                    <h5>
                                                        <a href="#cat-{{ $categorie->id }}" data-toggle="collapse"
                                                            class="collapsed" aria-expanded="false" role="button">
                                                            {{ $categorie->titre }}
                                                            <i class="accordion-indicator ti-angle-down"></i>
                                                        </a>
                                                    </h5>

                                                    <div class="collapse" id="cat-{{ $categorie->id }}"
                                                        data-parent="#shop-categories">
                                                        <div class="card-body">
                                                            <div class="inner_widget_link">
                                                                <ul>
                                                                    @foreach ($categorie->getSousCategories as $SousCategorie)
                                                                        @if ($SousCategorie->getPost->count() > 0)
                                                                            <li class="d-flex justify-content-between">
                                                                                <button class="btn-btn-shop-style"
                                                                                    type="button"
                                                                                    wire:click="filtre_sous_cat({{ $SousCategorie->id }})">
                                                                                    {{ $SousCategorie->titre }}
                                                                                </button>
                                                                                <span>
                                                                                    {{ $SousCategorie->getPost->count() }}
                                                                                </span>
                                                                            </li>
                                                                        @endif
                                                                    @endforeach
                                                                </ul>
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

                            <!-- Single Option -->
                            <div class="single_search_boxed">
                                <div class="widget-boxed-header">
                                    <h4><a href="#types" data-toggle="collapse" class="collapsed" aria-expanded="false"
                                            role="button">état</a></h4>
                                </div>
                                <div class="widget-boxed-body collapse" id="types" data-parent="#types">
                                    <div class="side-list no-border">
                                        <!-- Single Filter Card -->
                                        <div>
                                            <select name="etat" wire:model="etat" class="btn-btn-shop-style-select">
                                                <option value="">état de l'article</option>
                                                <option value="Neuf avec étiquettes">Neuf avec étiquettes</option>
                                                <option value="Neuf sans étiquettes">Neuf sans étiquettes</option>
                                                <option value="Très bon état">Très bon état</option>
                                                <option value="Bon état">Bon état</option>
                                                <option value="Usé">Usé</option>
                                            </select>
                                            @error('etat')
                                                <small class="form-text text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Single Option -->
                            <div class="single_search_boxed">
                                <div class="widget-boxed-header">
                                    <h4><a href="#occation" data-toggle="collapse" class="collapsed"
                                            aria-expanded="false" role="button">Localisation</a></h4>
                                </div>
                                <div class="widget-boxed-body collapse" id="occation" data-parent="#occation">
                                    <div class="side-list no-border">
                                        <!-- Single Filter Card -->
                                        <div class="single_filter_card">
                                            <div class="card-body pt-0">
                                                <div class="inner_widget_link">
                                                    <ul class="">
                                                        @foreach ($regions as $region)
                                                            @if ($region->getPost->count() > 0)
                                                                <li class="d-flex justify-content-between">
                                                                    <button class="btn-btn-shop-style" type="button"
                                                                        wire:click="filtre_sous_cat({{ $region->id }})">
                                                                        {{ $region->nom }}
                                                                    </button>
                                                                    <span>
                                                                        {{ $region->getPost->count() }}
                                                                    </span>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div wire:loading>
                                <div class="text-danger p-2">
                                    <x-Loading></x-Loading> Traitement...
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="reset" class="btn btn-md  bg-danger text-light fs-md ft-medium">
                                    Vider
                                    <i class="bi bi-x-lg"></i>
                                </button>

                                <button type="submit" class="btn btn-md  bg-dark text-light fs-md ft-medium">
                                    Filtrer
                                    <i class="bi bi-arrow-right-circle-fill"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-xl-9 col-lg-8 col-md-12 col-sm-12">

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <div class="border mb-3 mfliud">
                            <div class="d-flex justify-content-between p-2 m-0">
                                <div >
                                    <h6 class="mb-0">
                                        {{ $total }} éléments trouvés
                                    </h6>
                                </div>

                                <div >
                                    <div class="filter_wraps d-flex align-items-center justify-content-end m-start">
                                        <a href="/">
                                            <img src="/icons/logo.png" alt="" height="20">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- row -->
                <div class="row align-items-center rows-products">

                    @forelse ($posts as $post)
                        <!-- Single -->
                        <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                            <div class="product_grid card b-0">
                                <div class="badge bg-info text-white position-absolute ft-regular ab-left text-upper">
                                    {{ $post->statut }}
                                </div>
                                <div class="badge badge-like-post-count position-absolute ab-right text-upper">
                                    <i class="far fa-heart"></i>
                                    <span>
                                        {{ $post->getLike->count() }}
                                    </span>
                                </div>
                                <div class="card-body p-0">
                                    <div class="shop_thumb position-relative">
                                        <a class="card-img-top d-block overflow-hidden"
                                            href="/post/{{ $post->id }}">
                                            <img src="{{ Storage::url($post->photos[0] ?? '') }}" alt="..."></a>
                                    </div>
                                </div>
                                <div class="card-footer b-0 p-0 pt-2 bg-white">
                                    <div class="">
                                        <div class="d-flex justify-content-between">
                                            <div class="text-left">
                                                {{ $post->sous_categorie_info->titre }}
                                            </div>
                                            @if ($post->sous_categorie_info->categorie->luxury == 1)
                                                <div>
                                                    <span class="color">
                                                        <i class="bi bi-gem"></i>
                                                        LUXURY
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                    <div class="text-left">
                                        <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1">
                                            <a href="/post/{{ $post->id }}">

                                                {{ $post->titre }}
                                            </a>
                                        </h5>
                                        <div class="elis_rty color">
                                            <span class="ft-bold  fs-sm">
                                                {{ $post->getPrix() }} DH
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1 color">
                                        <i class="bi bi-info-circle"></i>
                                        Aucun résultat trouvé
                                    </h5>
                                </div>
                            </div>
                        </div>
                    @endforelse


                </div>
                <!-- row -->

                {{--  <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 text-center">
                        <a href="#" class="btn stretched-link borders m-auto"><i
                                class="lni lni-reload mr-2"></i>Load More</a>
                    </div>
                </div> --}}
            </div>

        </div>
    </div>

</section>
