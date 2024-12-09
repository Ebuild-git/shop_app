@extends('User.fixe')
@section('titre', 'Marketplace')
@section('body')




    <!-- ======================= Top Breadcrubms ======================== -->
    <div class="gray py-3">
        <div class="container">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{ route('shop') }}">
                                    Catégories
                                </a>
                            </li>
                            @if ($selected_categorie)
                                <li class="breadcrumb-item active" aria-current="page">
                                    <a href="shop?id_categorie={{ $selected_categorie->id }}">
                                        {{ $selected_categorie->titre }}
                                        @if ($selected_categorie->luxury == 1)
                                            <i class="bi bi-gem small color"></i>
                                        @endif
                                    </a>
                                </li>
                            @endif
                            @if ($selected_sous_categorie)
                                <li class="breadcrumb-item active" aria-current="page">
                                    <b class="color">
                                        {{ $selected_sous_categorie->titre }}
                                    </b>
                                </li>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ======================= Top Breadcrubms ======================== -->

    <!-- ======================= New Filter Container for Category Level ======================== -->
        <div class="category-filter-wrapper mobile-visible">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col d-flex">
                        <div class="price-filter-container">
                            <div class="custom-filter-option" id="filter-price-mobile">
                                <i class="fas fa-sort"></i>
                                <span>Trier par</span>
                            </div>
                            <div class="custom-dropdown-container" id="price-options-mobile">
                                <div class="custom-dropdown-item" data-value="low_to_high" onclick="updatePriceFilter('low_to_high')">Prix croissant</div>
                                <div class="custom-dropdown-item" data-value="high_to_low" onclick="updatePriceFilter('high_to_low')">Prix décroissant</div>
                                <div class="custom-dropdown-item" data-value="soldé" onclick="updatePriceFilter('soldé')">Articles Soldés</div>
                                @if (!$selected_categorie)
                                <div class="custom-dropdown-item" data-value="luxury" onclick="updatePriceFilter('luxury')">Luxury uniquement</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Filter by Condition Button and its Dropdown -->
                    <div style="margin-right: 10px;">
                        <div class="condition-filter-container">
                            <div class="custom-filter-option" id="filter-condition-category-mobile">
                                <i class="fas fa-tags"></i>
                                <span>Filtrer par état</span>
                            </div>
                            <!-- Condition Options Dropdown -->
                            <div class="custom-dropdown-container etat-dropdown" id="condition-options-category-mobile">
                                <div class="custom-dropdown-item" data-value="Neuf avec étiquettes" onclick="updateConditionFilter('Neuf avec étiquettes')">Neuf avec étiquettes</div>
                                <div class="custom-dropdown-item" data-value="Neuf sans étiquettes" onclick="updateConditionFilter('Neuf sans étiquettes')">Neuf sans étiquettes</div>
                                <div class="custom-dropdown-item" data-value="Très bon état" onclick="updateConditionFilter('Très bon état')">Très bon état</div>
                                <div class="custom-dropdown-item" data-value="Bon état" onclick="updateConditionFilter('Bon état')">Bon état</div>
                                <div class="custom-dropdown-item" data-value="Usé" onclick="updateConditionFilter('Usé')">Usé</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<!-- ======================= New Filter Container for Category Level ======================== -->
    <section class="middle" id="ancre">
        <div class="container">
            @if ($key)
                <h5>
                    <b>
                        Résultats de recherche pour : '{{ $key }}'
                    </b>
                </h5>
            @endif
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 p-xl-0 desktop-only">
                    <div class="search-sidebar sm-sidebar border @if ($key) hide-on-mobile @endif">
                        <div class="search-sidebar-body">
                            <!-- Single Option -->
                            <div class="single_search_boxed">
                                <div class="widget-boxed-header">
                                    @if ($selected_categorie)
                                        @if ($selected_sous_categorie)
                                            <h3 class="p-2">
                                                <b>{{ $selected_sous_categorie->titre }}</b>
                                            </h3>
                                        @else
                                            <div class="bg-color p-2">
                                                <a href="/shop" class="h6 text-white">Catégories</a>
                                            </div>
                                            <div class="strong p-2 pl-3">
                                                <a href="/shop" class="h6">
                                                    <i class="bi bi-arrow-left"></i>
                                                    <span class="strong">{{ $selected_categorie->titre }}</span>
                                                    @if ($selected_categorie->luxury == 1)
                                                        <span class="small color">
                                                            <i class="bi bi-gem"></i> Luxury
                                                        </span>
                                                    @endif
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        <div class="bg-color p-2">
                                            <div class="h6 text-white">Catégories</div>
                                        </div>
                                    @endif
                                </div>
                                <div class="widget-boxed-body">
                                    <div class="side-list no-border">
                                        <div class="filter-card" id="shop-categories">
                                            @if (!$selected_categorie)
                                                @foreach ($liste_categories as $categorie)
                                                    <div class="single_filter_card my-auto" id="list-categorie"
                                                        onclick="select_categorie({{ $categorie->id }})">
                                                        <button class="d-flex p-1 justify-content-between btn w-100">
                                                            <div class="d-flex justify-content-start">
                                                                <span>
                                                                    <img width="20" height="20" src="{{ Storage::url($categorie->small_icon) }}" />
                                                                    &nbsp;
                                                                </span>
                                                                <span>{{ $categorie->titre }}</span>
                                                            </div>
                                                            <div>
                                                                <span>
                                                                    @if ($categorie->luxury == 1)
                                                                        <span class="color small">
                                                                            <b><i class="bi bi-gem"></i> Luxury</b>
                                                                        </span>
                                                                        &nbsp;
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            @endif

                                            <div>
                                                @if ($selected_categorie)
                                                    @if (!$selected_sous_categorie)
                                                        <div class="card card-image-shop-categorie">
                                                            <img src="{{ Storage::url($selected_categorie->icon) }}"
                                                                alt="{{ $selected_categorie->icon }}" class="w-100">
                                                        </div>
                                                        <div class="color p-1">
                                                            <a href="/shop?id_categorie={{ $selected_categorie->id }}" class="color">
                                                                Tout les articles de {{ $selected_categorie->titre }}
                                                                @if ($selected_categorie->luxury == 1)
                                                                    <i class="bi bi-gem small color"></i>
                                                                @endif
                                                            </a>
                                                        </div>
                                                    @endif

                                                    @if ($selected_sous_categorie)
                                                    @else
                                                        <hr>
                                                        <div>
                                                            @foreach ($selected_categorie->getSousCategories as $sous_categorie)
                                                                <button class="btn w-100 mb-1 d-flex btn-sm justify-content-between"
                                                                    onclick="select_sous_categorie({{ $sous_categorie->id }})">
                                                                    <span>
                                                                        {{ $sous_categorie->titre }}
                                                                        @if ($selected_categorie->luxury == 1)
                                                                            <span class="color">
                                                                                <b><i class="bi bi-gem"></i></b>
                                                                            </span>
                                                                        @endif
                                                                    </span>
                                                                    <span>{{ $sous_categorie->getPost->where('statut','vente')->count() }}</span>
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="@if (!$selected_sous_categorie) d-none @endif">

                                    <div class="desktop-options">
                                        <div class="container mb-2">
                                            <div class="d-flex flex-wrap"></div>
                                        </div>
                                    </div>
                                </div>
                                @if ($selected_sous_categorie)
                                    <x-DynamicShopFilter :idsouscategorie="$selected_sous_categorie->id"></x-DynamicShopFilter>
                                @endif

                            </div>

                            @if ($selected_sous_categorie)
                                <!-- Single Option -->
                                <div class="single_search_boxed">
                                    <div class="widget-boxed-header">
                                        <h4>
                                            <button class="collapse-toggle" data-target="#types">
                                                état
                                                <span class="collapse-icon">
                                                    <i class="bi bi-plus-lg"></i>
                                                </span>
                                            </button>
                                        </h4>
                                    </div>
                                    <div class="widget-boxed-body collapse-content" id="types">
                                        <div class="side-list no-border">
                                            <div class="d-flex justify-content-start">
                                                <input type="radio" name="etat" value="Neuf avec étiquettes" onclick="updateConditionFilter('Neuf avec étiquettes')">
                                                <button type="button" class="btn-etat-shop">Neuf avec étiquettes</button>
                                                <span class="reset-x" onclick="resetSingleFilter(this)">&times;</span> <!-- X for reset -->
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <input type="radio" name="etat" value="Neuf sans étiquettes" onclick="updateConditionFilter('Neuf sans étiquettes')">
                                                <button type="button" class="btn-etat-shop">Neuf sans étiquettes</button>
                                                <span class="reset-x" onclick="resetSingleFilter(this)">&times;</span> <!-- X for reset -->
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <input type="radio" name="etat" value="Très bon état" onclick="updateConditionFilter('Très bon état')">
                                                <button type="button" class="btn-etat-shop">Très bon état</button>
                                                <span class="reset-x" onclick="resetSingleFilter(this)">&times;</span> <!-- X for reset -->
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <input type="radio" name="etat" value="Bon état" onclick="updateConditionFilter('Bon état')">
                                                <button type="button" class="btn-etat-shop">Bon état</button>
                                                <span class="reset-x" onclick="resetSingleFilter(this)">&times;</span> <!-- X for reset -->
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <input type="radio" name="etat" value="Usé" onclick="updateConditionFilter('Usé')">
                                                <button type="button" class="btn-etat-shop">Usé</button>
                                                <span class="reset-x" onclick="resetSingleFilter(this)">&times;</span> <!-- X for reset -->
                                            </div>
                                            @error('etat')
                                                <small class="form-text text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Single Option -->
                                {{-- <div class="single_search_boxed">
                                    <div class="widget-boxed-header">
                                        <h4>
                                            <button class="collapse-toggle" data-target="#prixs">
                                                prix
                                                <span class="collapse-icon">
                                                    <i class="bi bi-plus-lg"></i>
                                                </span>
                                            </button>
                                        </h4>
                                    </div>
                                    <div class="widget-boxed-body collapse-content" id="prixs">
                                        <div class="side-list no-border">
                                            <div class="d-flex justify-content-start">
                                                <input type="radio" name="ordre_prix" value="low_to_high" onclick="updatePriceFilter('low_to_high')" id="prix_asc">
                                                <span class="btn-etat-shop">Ordre croissant</span>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <input type="radio" name="ordre_prix" value="high_to_low" onclick="updatePriceFilter('high_to_low')" id="prix_desc">
                                                <span class="btn-etat-shop">Ordre décroissant</span>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <input type="radio" name="ordre_prix" value="soldé" onclick="updatePriceFilter('soldé')" id="solder">
                                                <span class="btn-etat-shop">Articles soldés</span>
                                            </div>
                                            @if (!$selected_categorie)
                                                <div class="d-flex justify-content-start">
                                                    <input type="checkbox" name="ordre_prix" value="luxury" onclick="updatePriceFilter('luxury')">
                                                    <span class="btn-etat-shop color">Uniquement <b><i class="bi bi-gem"></i> Luxury</b></span>
                                                </div>
                                            @endif
                                            @error('ordre')
                                                <small class="form-text text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="single_search_boxed">
                                    <div class="widget-boxed-header">
                                        <h4>
                                            <button class="collapse-toggle" data-target="#prixs">
                                                prix
                                                <span class="collapse-icon">
                                                    <i class="bi bi-plus-lg"></i>
                                                </span>
                                            </button>
                                        </h4>
                                    </div>
                                    <div class="widget-boxed-body collapse-content" id="prixs">
                                        <div class="side-list no-border">
                                            <div class="d-flex justify-content-start">
                                                <input type="radio" name="ordre_prix" value="low_to_high" onclick="updatePriceFilter('low_to_high')" id="prix_asc">
                                                <span class="btn-etat-shop">Ordre croissant</span>
                                                <span class="reset-x" onclick="resetSinglePriceFilter(this)">&times;</span> <!-- X for reset -->
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <input type="radio" name="ordre_prix" value="high_to_low" onclick="updatePriceFilter('high_to_low')" id="prix_desc">
                                                <span class="btn-etat-shop">Ordre décroissant</span>
                                                <span class="reset-x" onclick="resetSinglePriceFilter(this)">&times;</span> <!-- X for reset -->
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <input type="radio" name="ordre_prix" value="soldé" onclick="updatePriceFilter('soldé')" id="solder">
                                                <span class="btn-etat-shop">Articles soldés</span>
                                                <span class="reset-x" onclick="resetSinglePriceFilter(this)">&times;</span> <!-- X for reset -->
                                            </div>
                                            @if (!$selected_categorie)
                                                <div class="d-flex justify-content-start">
                                                    <input type="checkbox" name="ordre_prix" value="luxury" onclick="updatePriceFilter('luxury')">
                                                    <span class="btn-etat-shop color">Uniquement <b><i class="bi bi-gem"></i> Luxury</b></span>
                                                    <span class="reset-x" onclick="resetSinglePriceFilter(this)">&times;</span> <!-- X for reset -->
                                                </div>
                                            @endif
                                            @error('ordre')
                                                <small class="form-text text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @php
                    $selected_categorie_id = request()->get('id_categorie');
                    $selected_sous_categorie_id = request()->get('selected_sous_categorie');

                @endphp

                <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 p-xl-0 d-xl-none">
                    <div class="row mobile-view">
                        @if ($selected_categorie_id && !$selected_sous_categorie_id)
                            <div class="go-back-container">
                                <div class="go-back-message">
                                    <a href="javascript:void(0)" style="text-decoration: underline; color: #008080; z-index:1;" onclick="goBackToCategories()">
                                        Tout les articles de cette catégorie
                                    </a>
                                </div>
                            </div>
                        @elseif ($selected_sous_categorie_id)
                            <div class="go-back-container1 mb-2">
                                <span href="javascript:void(0)" style="text-decoration: underline; color: #008080; cursor: pointer; margin-left: 10px;" onclick="goBackToSubcategories()">
                                    <i class="bi bi-arrow-left-square-fill"></i> Retour à la liste des sous-catégories
                                </span>
                            </div>
                        @endif

                        <div class="scrollable-container">
                            @if (!$selected_sous_categorie_id)
                                <button class="scroll-btn left" onclick="scrollToLeft()"><i class="bi bi-arrow-left-short"></i></button>
                            @endif

                            <div class="subcategory-card-wrapper scrollable-wrapper" id="category-cards">
                                @if (!$selected_sous_categorie_id)
                                    @if (!$selected_categorie_id)
                                        @foreach ($liste_categories as $categorie)
                                            <div class="category-card p-1" id="list-categorie-{{ $categorie->id }}" onclick="select_categorie({{ $categorie->id }})">

                                                <button class="category-btn d-flex flex-column align-items-center p-1" style="height: 120px;">
                                                    <div style="height: 30px;">
                                                        <img class="category-icon" width="40" height="40" src="{{ Storage::url($categorie->small_icon) }}" />
                                                    </div>
                                                    <div style="height: 15px; margin-top: 5px;">
                                                        <span>{{ $categorie->titre }}</span>
                                                    </div>
                                                    <div style="height: 25px; margin-top: 5px;">
                                                        @if ($categorie->luxury == 1)
                                                            <span class="luxury-icon color small">
                                                                <b><i class="bi bi-gem"></i></b>
                                                            </span>
                                                        @else
                                                            <span style="visibility: hidden;">
                                                                <i class="bi bi-gem"></i>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </button>
                                            </div>
                                        @endforeach
                                    @else
                                        @php
                                            $selected_categorie = $liste_categories->firstWhere('id', $selected_categorie_id);
                                        @endphp
                                        @foreach ($selected_categorie->getSousCategories as $sous_categorie)
                                            <div class="subcategory-card p-2" onclick="select_sous_categorie({{ $sous_categorie->id }})">

                                                <button class="subcategory-btn d-flex flex-column align-items-center p-1" style="height: auto;">
                                                    <div class="d-flex justify-content-center" style="margin-bottom: 5px;">
                                                        <span>{{ $sous_categorie->titre }}</span>
                                                    </div>

                                                    <div class="d-flex align-items-center justify-content-center" style="width: 100%;gap: 5px;">
                                                        <span>{{ $sous_categorie->getPost->where('statut', 'vente')->count() }}</span>

                                                        @if ($selected_categorie->luxury == 1)
                                                            <span class="luxury-icon color small" style="margin-left: -1px;">
                                                                <b><i class="bi bi-gem"></i></b>
                                                            </span>
                                                        @else
                                                            <span style="visibility: hidden;">
                                                                <i class="bi bi-gem"></i>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </button>

                                            </div>
                                        @endforeach
                                    @endif
                                @endif
                            </div>

                            @if (!$selected_sous_categorie_id)
                                <button class="scroll-btn right" onclick="scrollToRight()"><i class="bi bi-arrow-right-short"></i></button>
                            @endif
                        </div>

                        @if ($selected_sous_categorie_id)

                            <div class="container-fluid">
                                <div class="mobile-options">
                                    <div class="container mb-2">
                                        <div class="d-flex flex-wrap"></div>
                                    </div>
                                </div>
                                <x-DynamicShopFilterMobile :idsouscategorie="$selected_sous_categorie_id"></x-DynamicShopFilterMobile>
                            </div>

                        @endif
                    </div>
                </div>



                <script>
                    function scrollToLeft() {
                    const containers = document.querySelectorAll('.scrollable-wrapper');
                    containers.forEach(container => {
                        if (isVisible(container)) {
                            if (container.scrollLeft > 0) {
                                container.scrollBy({ left: -lastScrollAmount, behavior: 'smooth' });
                            } else {
                                console.log("Already at the start, cannot scroll further left");
                            }
                        }
                    });
                }

                function scrollToRight() {
                    const containers = document.querySelectorAll('.scrollable-wrapper');
                    containers.forEach(container => {
                        if (isVisible(container)) {
                            lastScrollAmount = container.clientWidth * 0.8;
                            container.scrollBy({ left: lastScrollAmount, behavior: 'smooth' });
                        }
                    });
                }
                function isVisible(element) {
                    return element.offsetWidth > 0 && element.offsetHeight > 0;
                }


                </script>
                <script>
                    function toggleIdForScreenSize() {
                    var selectedOptionsDesktop = document.querySelector(".desktop-options");
                    var selectedOptionsMobile = document.querySelector(".mobile-options");

                    if (window.innerWidth <= 768) {
                        if (selectedOptionsDesktop) {
                            selectedOptionsDesktop.removeAttribute("id");
                        }
                        if (selectedOptionsMobile) {
                            selectedOptionsMobile.setAttribute("id", "Selected_options");
                        }
                    } else {
                        if (selectedOptionsMobile) {
                            selectedOptionsMobile.removeAttribute("id");
                        }
                        if (selectedOptionsDesktop) {
                            selectedOptionsDesktop.setAttribute("id", "Selected_options");
                        }
                    }
                }

                window.onload = toggleIdForScreenSize;
                window.onresize = toggleIdForScreenSize;

                </script>

                <div class="col-xl-9 col-lg-8 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <div class="mb-3 mfliud">
                                <div class="d-flex justify-content-end p-2 m-0">
                                    <div class="custom-dropdown-wrapper">
                                        <div class="custom-dropdown desktop-only">
                                            <div class="dropdown-selected">
                                                Trier par
                                                <i class="fas fa-chevron-down"></i>
                                            </div>

                                            <div class="dropdown-options">
                                                <div class="dropdown-option" data-value="" onclick="updatePriceFilter('')">Trier par</div>
                                                <div class="dropdown-option" data-value="low_to_high" onclick="updatePriceFilter('low_to_high')">Prix croissant</div>
                                                <div class="dropdown-option" data-value="high_to_low" onclick="updatePriceFilter('high_to_low')">Prix décroissant</div>
                                                <div class="dropdown-option" data-value="soldé" onclick="updatePriceFilter('soldé')">Articles Soldés</div>
                                                @if (!$selected_categorie)
                                                    <div class="dropdown-option" data-value="luxury" onclick="updatePriceFilter('luxury')">Luxury uniquement</div>
                                                @endif
                                            </div>
                                        </div>

                                        <select name="filtre-ordre" id="filtre-ordre" class="hidden-select" style="display: none;">
                                            <option value="">Trier par</option>
                                            <option value="prix_asc">Prix croissant</option>
                                            <option value="prix_desc">Prix décroissant</option>
                                            <option value="Soldé">Articles Soldés</option>
                                            @if (!$selected_categorie)
                                                <option value="luxury">Luxury uniquement</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center p-5" id="loading">
                        <img src="/icons/kOnzy.gif" alt="gif" height="50" width="50" srcset="">
                        <br>
                        <span class="color">
                            Recherche d'annonces...
                        </span>
                    </div>


                    <!-- row -->
                    <div class="row align-items-center rows-products">
                    </div>
                    <ul class="pagination" id="pagination-controls"></ul>
                </div>

            </div>
        </div>

    </section>

    <script>
        //initialisation
        var check_luxury_only = {{ $luxury_only ?? 'false' }};
        var key = "{{ $key ?? '' }}";
        var categorie = {{ $selected_categorie->id ?? 'null' }};
        var sous_categorie = {{ $selected_sous_categorie->id ?? 'null' }};
        var region = "";
        var etat = "";
        var ordre_prix = "";
        var proprietes = [];
        var options = [];
        var Taille = "";
        var Couleur = "";
        var Pointure = "";
        var ArticlePour = "";
        var Langue = "";
        var Tailleenchiffre = "";
        var Matiere = "";
        var PointureBeBe = "";
        var TailleBeBe = "";
        var PointureEnfant = "";
        var TailleEnfant = "";
        var MatiereSac = "";
        if (options.length > 0) {
            show_selected_options();
        }

        function show_selected_options() {
            var selected_options_div = document.getElementById("Selected_options");
            if (options.length > 0) {
                selected_options_div.innerHTML = "";

                options.forEach((options, index) => {
                    selected_options_div.innerHTML += "<div onclick='remove_selected_option(" + index + ")'>" +
                        options[1] + " <i class='ti-close small text-danger'></i> </div>";
                });
                selected_options_div.innerHTML +=
                    "<div class='reset_select_option' onclick='reset()'>Réinitialiser <i class='ti-close small'></i> </div>";
            }
        }

        function remove_selected_option(index) {
            total_option = options.length;
            options.splice(index, 1);
            show_selected_options();
            if (total_option == 1) {
                document.getElementById("Selected_options").innerHTML = "";
            }
        }


        function add_selected_option(type, nom) {
            var existeDeja = false;
            for (var i = 0; i < options.length; i++) {
                if (options[i][0] === type) {
                    options[i][1] = nom;
                    existeDeja = true;
                    break;
                }
            }
            if (!existeDeja) {
                options.push([type, nom]);
            }
            show_selected_options();
        }

        $(document).ready(function() {
            fetchProducts();
            $('.key-input').on('input', function() {
                key = $('#key').val();
                fetchProducts();
            });
        });

        function ancre() {
            $('html,body').animate({
                scrollTop: $("#ancre").offset().top
            }, 'slow');
        }


        function reset() {
            //reload page
            window.location.reload();
        }


        function choix_ordre_prix(ordre) {
            if (ordre == "prix_asc") {
                ordre_prix = "Asc";
                $("#filtre-ordre").val("prix_asc");
                add_selected_option("ordre_prix", "Ordre Croissant");
                fetchProducts();
            }
            if (ordre == "prix_desc") {
                ordre_prix = "Desc";
                $("#filtre-ordre").val("prix_desc");
                add_selected_option("ordre_prix", "Ordre Décroissant");
                fetchProducts();
            }
            if (ordre == "Soldé") {
                ordre_prix = "Soldé";
                $("#filtre-ordre").val("Soldé");
                add_selected_option("ordre_prix", "Article Soldé");
                fetchProducts();
            }
            if (ordre == "luxury") {
                check_luxury_only = "true";
                fetchProducts();
            }
        }

        function filtre_propriete_color(type, code, nom) {
            add_selected_option(type, nom);
            filtre_propriete(type, code);
        }

        function select_region(checkbox) {
            var checkboxes = document.getElementsByName('region');
            checkboxes.forEach(function(cb) {
                if (cb !== checkbox) {
                    cb.checked = false;
                }
            });
            _region = checkbox.value;
            if (_region == region) {
                region = "";
            } else {
                region = _region;
            }
            fetchProducts();
        }

        function choix_etat(checkbox) {
            var checkboxes = document.getElementsByName('etat');
            checkboxes.forEach(function(cb) {
                if (cb !== checkbox) {
                    cb.checked = false;
                }
            });
            _etat = checkbox.value;
            if (_etat == etat) {
                etat = "";
            } else {
                etat = _etat;
            }

            add_selected_option("etat", etat);
            fetchProducts();
        }
        function choix_etat1(checkbox) {
            var checkboxes = document.getElementsByName('etat');
            checkboxes.forEach(function(cb) {
                if (cb !== checkbox) {
                    cb.checked = false;
                }
            });
            _etat = checkbox.value;
            if (_etat == etat) {
                etat = "";
            } else {
                etat = _etat;
            }

            add_selected_option("etat", etat);
            fetchProducts();
        }

        function select_sous_categorie(id) {
            window.location.href = "{{ Request::fullUrl() }}&selected_sous_categorie=" + id;
            sous_categorie = id;
            fetchProducts();
        }


        function select_sous_categorie1(id) {
            window.location.href = "{{ Request::fullUrl() }}&selected_sous_categorie=" + id;
            sous_categorie = id;
            fetchProducts1();
        }
        function goBackToSubcategories() {
            window.location.href = "{{ Request::fullUrl() }}&selected_sous_categorie=";
        }
        function filtre_propriete(type, nom) {
            type = type.replace(/^\s+|\s+$/gm, '');
            var show = true;
            console.log("Type:", type, "Nom:", nom);

            if (type == 'Couleur' || type == 'couleur') {
                Couleur = nom;
                show = false;
            }
            if (type == 'Taille' || type == 'taille') {
                Taille = nom;

            }
            if (type == 'Article pour' || type == 'article pour') {
                ArticlePour = nom;
            }
            if (type == 'Langue' || type == 'langue') {
                Langue = nom;
            }
            if (type == 'Pointure' || type == 'pointure') {
                Pointure = nom;
            }
            if (type == 'Matière de chaussures' || type == 'matière de chaussures') {
                Matiere = nom;
            }
            if (type == 'Matière' || type == 'matière') {
                MatiereSac = nom;

            }
            if (type == 'Pointure Bébé' || type == 'Pointure bébé' || type == 'pointure bébé') {
                PointureBeBe = nom;
            }
            if (type == 'Taille Bébé' || type == 'taille bébé' || type == 'Taille bébé') {
                TailleBeBe = nom;
            }
            if (type == 'Pointure Enfant' || type == 'Pointure enfant' || type == 'pointure enfant') {
                PointureEnfant = nom;
            }
            if (type == 'Taille Enfant' || type == 'Taille enfant' || type == 'taille enfant') {
                TailleEnfant = nom;
            }
            if (show) {
                add_selected_option(type, nom);
            }


            let modifiedName = nom.replace(/\s/g, '');
            modifiedName = modifiedName.replace(/([!"#$%&'()*+,.\/:;<=>?@[\\\]^`{|}~])/g, '\\$1');

            var button = $("#btn-option-" + modifiedName);

            if (button.length) {
                if (button.hasClass("bg-red")) {
                    button.removeClass("bg-red");
                    proprietes = '';
                } else {
                    $("button[id^='btn-option-']").removeClass("bg-red");
                    button.addClass("bg-red");
                    _proprietes = {
                        type: type,
                        valeur: nom
                    };
                    proprietes = _proprietes;
                }
            } else {
                console.error("Button with ID '#btn-option-" + modifiedName + "' not found.");
            }
            fetchProducts();
        }

        $("#filtre-ordre").on("change", function() {
            let ordre = $(this).val();

            if (ordre == "prix_asc") {
                ordre_prix = "Asc";
                $("#prix_asc").prop('checked', true);
                add_selected_option("ordre_prix", "Ordre Croissant");
                check_luxury_only = null;
                fetchProducts();
            }
            if (ordre == "prix_desc") {
                ordre_prix = "Desc";
                $("#prix_desc").prop('checked', true);
                add_selected_option("ordre_prix", "Ordre Décroissant");
                check_luxury_only = null;
                fetchProducts();
            }
            if (ordre == "Soldé") {
                ordre_prix = "Soldé";
                $("#solder").prop('checked', true);
                add_selected_option("ordre_prix", "Article Soldé");
                check_luxury_only = null;
                fetchProducts();
            }
            if (ordre == "luxury") {
                ordre_prix = "";
                check_luxury_only = "true";
                fetchProducts();
            }
        });


        function select_categorie(id) {
            categorie = id;
            sous_categorie = "";
            window.location.href = "/shop?id_categorie=" + id;
            fetchProducts();
        }

        function fetchProducts(page = 1) {
            var ordre_prix = window.currentPriceOrder || $('#priceOrderSelect').val();
            var etat = window.currentCondition;

            $("#loading").show("show");
            //ancre();
            $.post(
                "/recherche?page=" + page, {
                    etat: etat,
                    key: key,
                    region: region,
                    ordre_prix: ordre_prix,
                    check_luxury: check_luxury_only,
                    categorie: categorie,
                    sous_categorie: sous_categorie,
                    Taille: Taille,
                    Couleur: Couleur,
                    Pointure: Pointure,
                    ArticlePour: ArticlePour,
                    Tailleenchiffre: Tailleenchiffre,
                    Matiere: Matiere,
                    PointureBeBe: PointureBeBe,
                    TailleBeBe: TailleBeBe,
                    PointureEnfant: PointureEnfant,
                    TailleEnfant: TailleEnfant,
                    MatiereSac: MatiereSac,
                    _token: $('meta[name="csrf-token"]').attr('content')
                }, // Passer la valeur de la recherche comme paramètre
                function(data, status) {

                    if (status === "success") {
                        $(".rows-products").empty();
                        $("#SugestionProprietes").empty();
                        $(".rows-products").html(data.html);
                        $("#total_post").text(data.total);
                        renderPagination(data.data);
                        $("#total_show_post").text(data.count_resultat);
                        $("#loading").hide("show");
                        if(data.count_resultat == 0){
                            $(".rows-products").html("<div class='col-sm-6 mx-auto text-center'>Aucun résultat pour vos critères de recherche.</div>");
                        }
                    }
                }

            );
        }


        function refreshScrollSettings() {
            const container = document.getElementById('category-cards');
            if (container.clientWidth < container.scrollWidth) {
                lastScrollAmount = container.clientWidth * 0.8;
            }
        }
        function select_categorie1(id, categorieName) {
                categorie = id;
                sous_categorie = "";
                window.location.href = "/shop?id_categorie=" + id;

                document.getElementById('category-cards').innerHTML = `
                    <div class="go-back-message">
                        <a href="javascript:void(0)" class="small text-primary" style="text-decoration: underline;" onclick="goBackToCategories()">
                            Tout les articles de cette catégorie.
                        </a>
                        <div class="subcategory-card-wrapper">
                            <!-- Subcategory cards will go here -->
                        </div>
                    </div>
                `;
                refreshScrollSettings();
            }

            function goBackToCategories() {
                window.location.href = "/shop";
            }


          // Function to update the price filter based on selected value
        function updatePriceFilter(priceOrder) {
            let backendPriceOrder;
            if (priceOrder === 'low_to_high') {
                backendPriceOrder = 'Asc';
            } else if (priceOrder === 'high_to_low') {
                backendPriceOrder = 'Desc';
            } else if (priceOrder === 'soldé') {
                backendPriceOrder = 'Soldé';
            } else if (priceOrder === 'luxury') {
                backendPriceOrder = 'Luxury';
            }
            window.currentPriceOrder = backendPriceOrder;
            fetchProducts(); // Fetch the products based on the updated filter

            // Hide all "X" buttons
            document.querySelectorAll('.reset-x').forEach((span) => {
                span.style.display = 'none';
            });

            // Show "X" for the selected filter only
            let selectedElement = document.querySelector(`input[name="ordre_prix"][value="${priceOrder}"], input[name="ordre_prix"][value="luxury"]`);
            if (selectedElement) {
                let resetX = selectedElement.parentElement.querySelector('.reset-x');
                resetX.style.display = 'inline'; // Show the "X" for the selected filter
            }
        }

        // Function to handle the click event on "X" to reset the price filter
        function resetSinglePriceFilter(element) {
            // Find the related radio button or checkbox
            let radioOrCheckbox = element.parentElement.querySelector('input[type="radio"], input[type="checkbox"]');
            radioOrCheckbox.checked = false; // Uncheck the radio button or checkbox

            // Hide the "X" button after reset
            element.style.display = 'none';

            // Call the reset filter logic
            updatePriceFilter(''); // Reset the filter by passing an empty condition
        }

        // Initialize: Hide "X" when no checkbox/radio is selected
        document.querySelectorAll('input[name="ordre_prix"]').forEach((input) => {
            let span = input.parentElement.querySelector('.reset-x');
            span.style.display = 'none'; // Hide "X" initially
            input.addEventListener('click', function() {
                // When radio/checkbox is selected, show the "X"
                updatePriceFilter(this.value);
            });
        });

       // Function to update the condition filter based on the selected value
function updateConditionFilter(condition) {
    window.currentCondition = condition; // Set the current filter condition
    fetchProducts(); // Fetch the products based on the updated filter

    // Hide all "X" buttons first
    document.querySelectorAll('.reset-x').forEach((span) => {
        span.style.display = 'none';
    });

    // Show "X" next to the selected radio button
    let selectedRadio = document.querySelector(`input[name="etat"][value="${condition}"]`);
    if (selectedRadio) {
        let resetX = selectedRadio.parentElement.querySelector('.reset-x');
        resetX.style.display = 'inline'; // Show the "X" for the selected filter
    }
}

// Function to handle the click event on "X" to reset the filter
function resetSingleFilter(element) {
    // Find the related radio button
    let radio = element.parentElement.querySelector('input[type="radio"]');
    radio.checked = false; // Uncheck the radio button

    // Hide the "X" button after reset
    element.style.display = 'none';

    // Call the reset filter logic
    updateConditionFilter(''); // Reset the filter by passing an empty condition
}

// Initialize: Hide "X" when no radio is selected
document.querySelectorAll('input[name="etat"]').forEach((radio) => {
    let span = radio.parentElement.querySelector('.reset-x');
    span.style.display = 'none'; // Hide "X" initially
    radio.addEventListener('click', function() {
        // When radio is selected, show the "X"
        updateConditionFilter(this.value);
    });
});

        function fetchProducts1(page = 1) {
            var ordre_prix = window.currentPriceOrder || $('#priceOrderSelect').val();
            var etat = window.currentCondition;

            $("#loading").show("show");
            //ancre();
            $.post(
                "/recherche?page=" + page, {
                    etat: etat,
                    key: key,
                    region: region,
                    ordre_prix: ordre_prix,
                    check_luxury: check_luxury_only,
                    categorie: categorie,
                    sous_categorie: sous_categorie,
                    Taille: Taille,
                    Couleur: Couleur,
                    Pointure: Pointure,
                    ArticlePour: ArticlePour,
                    Tailleenchiffre: Tailleenchiffre,
                    Matiere: Matiere,
                    PointureBeBe: PointureBeBe,
                    TailleBeBe: TailleBeBe,
                    PointureEnfant: PointureEnfant,
                    TailleEnfant: TailleEnfant,
                    MatiereSac: MatiereSac,
                    _token: $('meta[name="csrf-token"]').attr('content')
                }, // Passer la valeur de la recherche comme paramètre
                function(data, status) {
                    if (status === "success") {
                        $(".rows-products").empty();
                        $("#SugestionProprietes").empty();
                        $(".rows-products").html(data.html);
                        $("#total_post").text(data.total);
                        renderPagination(data.data);
                        $("#total_show_post").text(data.count_resultat);
                        $("#loading").hide("show");
                        if(data.count_resultat == 0){
                            $(".rows-products").html("<div class='col-sm-6 mx-auto text-center'>Aucun résultat pour vos critères de recherche.</div>");
                        }
                    }
                }
                console.log("Matiere in fetchProducts:", Matiere);

            );
        }


        function renderPagination(data) {
            const paginationControls = $('#pagination-controls');
            paginationControls.empty();
            if (data.data.length > 0) {
                let startPage, endPage;
                const totalPages = data.last_page;
                const currentPage = data.current_page;

                if (totalPages <= 3) {
                    startPage = 1;
                    endPage = totalPages;
                } else {
                    if (currentPage <= 2) {
                        startPage = 1;
                        endPage = 3;
                    } else if (currentPage + 1 >= totalPages) {
                        startPage = totalPages - 2;
                        endPage = totalPages;
                    } else {
                        startPage = currentPage - 1;
                        endPage = currentPage + 1;
                    }
                }

                if (currentPage > 1) {
                    paginationControls.append('<li data-page="' + (currentPage - 1) + '">Précédent</li>');
                }
                for (let i = startPage; i <= endPage; i++) {
                    const activeClass = currentPage === i ? 'active' : '';
                    paginationControls.append('<li data-page="' + i + '" class="' + activeClass + '">' + i + '</li>');
                }
                if (currentPage < totalPages) {
                    paginationControls.append('<li data-page="' + (currentPage + 1) + '">Suivant</li>');
                }
            }
        }

        $(document).on('click', '.pagination li', function() {
            const page = $(this).data('page');
            fetchProducts(page);
            ancre();
        });

        $(document).on('click', '.pagination li', function() {
            const page = $(this).data('page');
            fetchProducts(page);
            ancre();
        });
    </script>

    <style>
        .pagination {
            display: flex;
            list-style: none;
        }

        .pagination li {
            margin: 0 5px;
            cursor: pointer;
            border: solid 2px #008080;
            padding: 0px 10px 0px 10px;
            border-radius: 5px;
        }

        .pagination li:hover {
            background-color: #008080;
            color: white;
        }

        .pagination .active {
            font-weight: bold;
            background-color: #008080;
            color: white;
        }
        @media (max-width: 768px) {
            .hide-on-mobile {
                display: none;
            }
        }

        .custom-dropdown-wrapper {
            position: relative;
            width: 190px;
            font-family: 'Arial', sans-serif;
            user-select: none;
        }

        .custom-dropdown {
            position: relative;
        }

        .dropdown-selected {
            background-color: #ffffff;
            border: 1px solid #ced4da;
            border-radius: 25px;
            padding: 10px 15px;
            font-size: 13px;
            color: #495057;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: border-color 0.3s ease;
        }

        .dropdown-selected:hover,
        .custom-dropdown.active .dropdown-selected {
            border-color: #008080;
        }

        .dropdown-selected i {
            font-size: 13px;
            color: #495057;
            transition: transform 0.3s ease;
        }

        .dropdown-options {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background-color: #ffffff;
            border: 1px solid #ced4da;
            border-radius: 10px;
            margin-top: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 100;
        }

        .dropdown-option {
            padding: 10px 15px;
            font-size: 13px;
            color: #495057;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .dropdown-option:hover {
            background-color: #f8f9fa;
        }

        .dropdown-option:last-child {
            border-radius: 0 0 10px 10px;
        }

        .custom-dropdown.active .dropdown-options {
            display: block;
        }

        .custom-dropdown.active .dropdown-selected i {
            transform: rotate(180deg);
        }

    </style>

@endsection
