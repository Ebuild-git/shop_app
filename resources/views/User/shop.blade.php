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


    <div class="sorting-filter-wrapper mobile-visible">
        <div class="container">
            <div class="row align-items-center">
                <!-- Filter by Condition Button on the Left -->
                <div class="col d-flex">
                    <div class="filter-option" id="filter-condition-mobile" onclick="toggleDropdown('condition-options-mobile')">
                        <i class="fas fa-filter"></i>
                        <span>Filtrer par état</span>
                    </div>
                </div>
                <!-- Sort by Button in the Middle -->
                <div class="col d-flex justify-content-center">
                    <div class="filter-option" id="trier-par-mobile" onclick="toggleDropdown('sorting-options-mobile')">
                        <i class="fas fa-sort"></i>
                        <span>Trier par</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sorting Options Dropdown -->
        <div class="dropdown-options-mobile" id="sorting-options-mobile" style="display: none;">
            <div class="dropdown-option" data-value="">Trier par</div>
            <div class="dropdown-option" data-value="prix_asc">Prix croissant</div>
            <div class="dropdown-option" data-value="prix_desc">Prix décroissant</div>
            <div class="dropdown-option" data-value="Soldé">Articles Soldés</div>
            @if (!$selected_categorie)
            <div class="dropdown-option" data-value="luxury">Luxury uniquement</div>
            @endif
        </div>

        <!-- Condition Filter Dropdown -->
        <div class="dropdown-options-mobile" id="condition-options-mobile" style="display: none;">
            <div class="dropdown-option">
                <input type="radio" name="etat" value="Neuf avec étiquettes" id="etat-neuf-avec-etiquettes" onclick="choix_etat(this)">
                <label for="etat-neuf-avec-etiquettes">Neuf avec étiquettes</label>
            </div>
            <div class="dropdown-option">
                <input type="radio" name="etat" value="Neuf sans étiquettes" id="etat-neuf-sans-etiquettes" onclick="choix_etat(this)">
                <label for="etat-neuf-sans-etiquettes">Neuf sans étiquettes</label>
            </div>
            <div class="dropdown-option">
                <input type="radio" name="etat" value="Très bon état" id="etat-tres-bon" onclick="choix_etat(this)">
                <label for="etat-tres-bon">Très bon état</label>
            </div>
            <div class="dropdown-option">
                <input type="radio" name="etat" value="Bon état" id="etat-bon" onclick="choix_etat(this)">
                <label for="etat-bon">Bon état</label>
            </div>
            <div class="dropdown-option">
                <input type="radio" name="etat" value="Usé" id="etat-use" onclick="choix_etat(this)">
                <label for="etat-use">Usé</label>
            </div>
            @error('etat')
            <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
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
                                    <div class="container mb-2">
                                        <div id="Selected_options" class="d-flex flex-wrap"></div>
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
                                                    <i class="bi bi-plus-lg"></i> <!-- Initial icon as plus -->
                                                </span>
                                            </button>
                                        </h4>
                                    </div>
                                    <div class="widget-boxed-body collapse-content" id="types">
                                        <div class="side-list no-border">
                                            <div class="d-flex justify-content-start">
                                                <input type="radio" name="etat" value="Neuf avec étiquettes" onclick="choix_etat(this)">
                                                <button type="button" class="btn-etat-shop">Neuf avec étiquettes</button>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <input type="radio" name="etat" value="Neuf sans étiquettes" onclick="choix_etat(this)">
                                                <button type="button" class="btn-etat-shop">Neuf sans étiquettes</button>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <input type="radio" name="etat" value="Très bon état" onclick="choix_etat(this)">
                                                <button type="button" class="btn-etat-shop">Très bon état</button>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <input type="radio" name="etat" value="Bon état" onclick="choix_etat(this)">
                                                <button type="button" class="btn-etat-shop">Bon état</button>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <input type="radio" name="etat" value="Usé" onclick="choix_etat(this)">
                                                <button type="button" class="btn-etat-shop">Usé</button>
                                            </div>
                                            @error('etat')
                                                <small class="form-text text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Single Option -->
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
                                                <input type="radio" name="ordre_prix" value="Asc" onclick="choix_ordre_prix('prix_asc')" id="prix_asc">
                                                <span class="btn-etat-shop">Ordre croissant</span>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <input type="radio" name="ordre_prix" value="Desc" onclick="choix_ordre_prix('prix_desc')" id="prix_desc">
                                                <span class="btn-etat-shop">Ordre décroissant</span>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <input type="radio" name="ordre_prix" value="Soldé" onclick="choix_ordre_prix('Soldé')" id="solder">
                                                <span class="btn-etat-shop">Articles soldés</span>
                                            </div>
                                            @if (!$selected_categorie)
                                                <div class="d-flex justify-content-start">
                                                    <input type="checkbox" name="ordre_prix" value="Desc" onclick="choix_ordre_prix('luxury')">
                                                    <span class="btn-etat-shop color">Uniquement <b><i class="bi bi-gem"></i> Luxury</b></span>
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
                        <div class="scrollable-container">
                            <!-- Conditional rendering of scroll buttons based on whether a subcategory is selected -->
                            @if (!$selected_sous_categorie_id)
                                <button class="scroll-btn left" onclick="scrollToLeft()"><i class="bi bi-arrow-left-short"></i></button>
                            @endif

                            <div class="subcategory-card-wrapper scrollable-wrapper" id="category-cards">
                                @if (!$selected_sous_categorie_id)
                                    <!-- Show categories or subcategories cards if no subcategory is selected -->
                                    @if (!$selected_categorie_id)
                                        @foreach ($liste_categories as $categorie)
                                            <div class="category-card p-1" id="list-categorie-{{ $categorie->id }}" onclick="select_categorie1({{ $categorie->id }})">
                                                <button class="category-btn d-flex flex-column p-1">
                                                    <img class="category-icon" width="40" height="40" src="{{ Storage::url($categorie->small_icon) }}" />
                                                    <span>{{ $categorie->titre }}</span>
                                                    @if ($categorie->luxury == 1)
                                                        <span class="luxury-icon color small">
                                                            <b><i class="bi bi-gem"></i></b>
                                                        </span>
                                                    @endif
                                                </button>
                                            </div>
                                        @endforeach
                                    @else
                                        <!-- Go Back Message -->
                                        <div class="go-back-container">
                                            <div class="go-back-message">
                                                <a href="javascript:void(0)" style="text-decoration: underline; color: #008080;" onclick="goBackToCategories()">
                                                    Tout les articles de cette catégorie.
                                                </a>
                                            </div>
                                        </div>
                                        <!-- Subcategory Cards -->
                                        @php
                                            $selected_categorie = $liste_categories->firstWhere('id', $selected_categorie_id);
                                        @endphp
                                        @foreach ($selected_categorie->getSousCategories as $sous_categorie)
                                            <div class="subcategory-card p-2" onclick="select_sous_categorie1({{ $sous_categorie->id }})">
                                                <button class="subcategory-btn d-flex flex-column p-1">
                                                    <span>{{ $sous_categorie->titre }}</span>
                                                    @if ($selected_categorie->luxury == 1)
                                                        <span class="luxury-icon color small">
                                                            <b><i class="bi bi-gem"></i></b>
                                                        </span>
                                                    @endif
                                                    <span>{{ $sous_categorie->getPost->where('statut', 'vente')->count() }}</span>
                                                </button>
                                            </div>
                                        @endforeach
                                    @endif
                                @else
                                    <!-- Display 'Filter by' text when subcategory is selected -->
                                    {{-- <div class="text-center p-2">
                                        <h4>Filter by:</h4>
                                    </div> --}}

                                @endif
                            </div>

                            @if (!$selected_sous_categorie_id)
                                <button class="scroll-btn right" onclick="scrollToRight()"><i class="bi bi-arrow-right-short"></i></button>
                            @endif
                        </div>

                        @if ($selected_sous_categorie_id)

                            <div class="container-fluid">
                                <div id="Selected_options" class="d-flex flex-wrap"></div>

                                <x-DynamicShopFilterMobile :idsouscategorie="$selected_sous_categorie_id"></x-DynamicShopFilterMobile>
                            </div>
                        @endif


                    </div>
                </div>



                <script>
                    function scrollToLeft() {
                    const containers = document.querySelectorAll('.scrollable-wrapper');
                    containers.forEach(container => {
                        if (isVisible(container)) {  // Assuming you have an `isVisible` function to check if the element is currently displayed
                            console.log("Current Scroll Position:", container.scrollLeft);
                            if (container.scrollLeft > 0) {
                                container.scrollBy({ left: -lastScrollAmount, behavior: 'smooth' });
                                console.log("Scrolling Left by:", -lastScrollAmount);
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
                            lastScrollAmount = container.clientWidth * 0.8;  // Update last scroll amount
                            console.log("Current Scroll Position:", container.scrollLeft);
                            console.log("Scrolling Right by:", lastScrollAmount);
                            container.scrollBy({ left: lastScrollAmount, behavior: 'smooth' });
                        }
                    });
                }
                function isVisible(element) {
                    return element.offsetWidth > 0 && element.offsetHeight > 0;
                }


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
                                                <div class="dropdown-option" data-value="">Trier par</div>
                                                <div class="dropdown-option" data-value="prix_asc">Prix croissant</div>
                                                <div class="dropdown-option" data-value="prix_desc">Prix décroissant</div>
                                                <div class="dropdown-option" data-value="Soldé">Articles Soldés</div>
                                                @if (!$selected_categorie)
                                                    <div class="dropdown-option" data-value="luxury">Luxury uniquement</div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Hidden select element to retain original options and form behavior -->
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

                    {{-- suggestion des attributs des propriétés --}}

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

        if (options.length > 0) {
            show_selected_options();
        }





        //afficher les options selectionner qui sont dans options dans la div Selected_options
        function show_selected_options() {
            var selected_options_div = document.getElementById("Selected_options");
            if (options.length > 0) {
                selected_options_div.innerHTML = "";
                console.log(options);
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
            // Vérifier si la paire type et nom existe déjà si ty type existe on change le nom
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
            // Faire la requête initiale au chargement de la page
            fetchProducts();

            // Ajouter un écouteur d'événements pour la saisie dans le champ de recherche
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

        function filtre_propriete(type, nom) {
            type = type.replace(/^\s+|\s+$/gm, '');
            var show = true;






            //debut brouillons
            if (type == 'Couleur' || type == 'couleur') {
                Couleur = nom;
                show = false;
            }
            if (type == 'Taille' || type == 'taille') {
                if(Tailleenchiffre != ""){
                    sweet("Opération impossible");
                    return;
                 }
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
            if (type == 'Taille en chiffre' || type == 'taille en chiffre') {
                 //se rasurer que on ne sellectionne pas en meme temps la taille et la taille en chiffre
                 if(Taille != ""){
                    sweet("Opération impossible");
                    return;
                 }
                Tailleenchiffre = nom;
            }
            //fin brouillons
            if (show) {
                add_selected_option(type, nom);
            }

            let modifiedName = nom.replace(/\s/g, '');
            var button = $("#btn-option-" + modifiedName);

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
                proprietes = proprietes
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
                lastScrollAmount = container.clientWidth * 0.8;  // Recalculate scroll amount based on new content width
            }
        }
        function select_categorie1(id, categorieName) {
                categorie = id;
                sous_categorie = "";

                // Redirect to the new URL
                window.location.href = "/shop?id_categorie=" + id;

                // Show the go-back message and subcategory cards inline
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
                // Redirect to the categories view without the selected category
                window.location.href = "/shop";
            }




        function fetchProducts1(page = 1) {
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


    function renderPagination(data) {
    const paginationControls = $('#pagination-controls');
    paginationControls.empty();

    // Only render pagination if there are items
    if (data.data.length > 0) {
        let startPage, endPage;
        const totalPages = data.last_page;
        const currentPage = data.current_page;

        if (totalPages <= 3) {
            // Show all pages if there are 3 or fewer
            startPage = 1;
            endPage = totalPages;
        } else {
            // Determine the start and end pages based on the current page
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

        // Add "Previous" button if not on the first page
        if (currentPage > 1) {
            paginationControls.append('<li data-page="' + (currentPage - 1) + '">Précédent</li>');
        }

        // Add the page numbers within the range
        for (let i = startPage; i <= endPage; i++) {
            const activeClass = currentPage === i ? 'active' : '';
            paginationControls.append('<li data-page="' + i + '" class="' + activeClass + '">' + i + '</li>');
        }

        // Add "Next" button if not on the last page
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
    <script>
  document.addEventListener('DOMContentLoaded', function () {
    const dropdown = document.querySelector('.custom-dropdown');
    const selected = dropdown.querySelector('.dropdown-selected');
    const options = dropdown.querySelectorAll('.dropdown-option');
    const hiddenSelect = document.querySelector('#filtre-ordre');

    // Toggle dropdown on click
    selected.addEventListener('click', function () {
        dropdown.classList.toggle('active');
    });

    // Handle option selection
    options.forEach(option => {
        option.addEventListener('click', function () {
            const value = this.getAttribute('data-value');
            const text = this.textContent;

            // Update the displayed selected option
            selected.innerHTML = `${text} <i class="fas fa-chevron-down"></i>`;

            // Update the hidden select value
            hiddenSelect.value = value;

            // Trigger a change event or form submission (adjust as needed)
            hiddenSelect.dispatchEvent(new Event('change'));

            // Close the dropdown
            dropdown.classList.remove('active');
        });
    });

    // Close dropdown if clicked outside
    document.addEventListener('click', function (event) {
        if (!dropdown.contains(event.target)) {
            dropdown.classList.remove('active');
        }
    });
});



document.addEventListener('DOMContentLoaded', function () {
    const trierParButton = document.getElementById('trier-par-mobile');
    const sortingOptions = document.getElementById('sorting-options-mobile');
    const filtreOrdreSelect = document.getElementById('filtre-ordre');
    const trierParText = trierParButton.querySelector('span');
    const filterConditionButton = document.getElementById('filter-condition-mobile');
    const conditionOptions = document.getElementById('condition-options-mobile');
    const dynamicFilterButton = document.getElementById('dynamic-filter-toggle'); // Assuming this button exists
    const dynamicFilterOptions = document.getElementById('dynamic-filter-mobile'); // Assuming this dropdown exists

    function closeAllDropdowns() {
        sortingOptions.style.display = 'none';
        conditionOptions.style.display = 'none';
        if (dynamicFilterOptions) dynamicFilterOptions.style.display = 'none'; // Ensure dynamic filter is also closed if exists
    }

    trierParButton.addEventListener('click', function (event) {
        event.stopPropagation();
        closeAllDropdowns(); // Close others before toggling this one
        sortingOptions.style.display = (sortingOptions.style.display === 'none' || sortingOptions.style.display === '') ? 'block' : 'none';
    });

    filterConditionButton.addEventListener('click', function (event) {
        event.stopPropagation();
        closeAllDropdowns(); // Close others before toggling this one
        conditionOptions.style.display = (conditionOptions.style.display === 'none' || conditionOptions.style.display === '') ? 'block' : 'none';
    });

    // If dynamic filter button exists
    if (dynamicFilterButton) {
        dynamicFilterButton.addEventListener('click', function (event) {
            event.stopPropagation();
            closeAllDropdowns(); // Close others before toggling this one
            dynamicFilterOptions.style.display = (dynamicFilterOptions.style.display === 'none' || dynamicFilterOptions.style.display === '') ? 'block' : 'none';
        });
    }

    document.querySelectorAll('#sorting-options-mobile .dropdown-option').forEach(option => {
        option.addEventListener('click', function () {
            const value = this.getAttribute('data-value');
            const optionText = this.textContent;
            filtreOrdreSelect.value = value;
            filtreOrdreSelect.dispatchEvent(new Event('change'));
            trierParText.textContent = optionText;
            closeAllDropdowns(); // Close dropdown after selection
        });
    });

    // Close dropdowns if clicked outside
    document.addEventListener('click', function (event) {
        if (!trierParButton.contains(event.target) && !sortingOptions.contains(event.target) &&
            !filterConditionButton.contains(event.target) && !conditionOptions.contains(event.target) &&
            (!dynamicFilterButton || (!dynamicFilterButton.contains(event.target) && !dynamicFilterOptions.contains(event.target)))) {
            closeAllDropdowns();
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.search-sidebar');
    const sortingOptions = document.querySelectorAll('#sorting-options-mobile .dropdown-option');
    const filterOptions = document.querySelectorAll('#condition-options-mobile .dropdown-option');
    const dropdowns = document.querySelectorAll('.dropdown');

    function closeDropdown(dropdown) {
        dropdown.classList.remove('show');
    }

    sortingOptions.forEach(option => {
        option.addEventListener('click', function () {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('hide-on-mobile', option.getAttribute('data-value') !== "");
                dropdowns.forEach(dropdown => closeDropdown(dropdown));
            }
        });
    });

    filterOptions.forEach(option => {
        option.addEventListener('click', function () {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('hide-on-mobile', option.getAttribute('data-value') !== "");
                dropdowns.forEach(dropdown => closeDropdown(dropdown));
            }
        });
    });
});

    </script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggles = document.querySelectorAll('.collapse-toggle');

    toggles.forEach(toggle => {
        toggle.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const target = document.querySelector(targetId);
            const icon = this.querySelector('.collapse-icon i');

            if (target) {
                if (target.classList.contains('active')) {
                    target.classList.remove('active');
                    this.setAttribute('aria-expanded', 'false');
                    icon.classList.replace('fa-minus', 'fa-plus'); // Change to plus icon
                } else {
                    target.classList.add('active');
                    this.setAttribute('aria-expanded', 'true');
                    icon.classList.replace('fa-plus', 'fa-minus'); // Change to minus icon
                }
            }
        });
    });
});


</script>

@endsection
