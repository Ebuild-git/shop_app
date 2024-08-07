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
                            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
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

                <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 p-xl-0">
                    <div class="search-sidebar sm-sidebar border">
                        <div class="search-sidebar-body">
                            <!-- Single Option -->
                            <div class="single_search_boxed ">
                                <div class="widget-boxed-header ">
                                    @if ($selected_categorie)
                                        @if ($selected_sous_categorie)
                                            <h3 class="p-2">
                                                <b>{{ $selected_sous_categorie->titre }}</b>
                                            </h3>
                                        @else
                                            <div class="bg-color p-2">
                                                <a href="/shop" class="h6 text-white">
                                                    Catégories
                                                </a>
                                            </div>
                                            <div class="strong p-2 pl-3">
                                                <a href="/shop" class="h6">
                                                    <i class="bi bi-arrow-left"></i>
                                                    <span class="strong">
                                                        {{ $selected_categorie->titre }}
                                                    </span>
                                                    @if ($selected_categorie->luxury == 1)
                                                        <span class="small color">
                                                            <i class="bi bi-gem "></i>
                                                            Luxury
                                                        </span>
                                                    @endif
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        <div class="bg-color p-2">
                                            <div class="h6 text-white">
                                                Catégories
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="widget-boxed-body">
                                    <div class="side-list no-border">
                                        <div class="filter-card " id="shop-categories">
                                            @if (!$selected_categorie)
                                                @foreach ($liste_categories as $categorie)
                                                    <!-- Single Filter Card categorie -->
                                                    <div class="single_filter_card my-auto" id="list-categorie"
                                                        onclick="select_categorie({{ $categorie->id }})">
                                                        <button class="d-flex  p-1  justify-content-between btn w-100">
                                                            <div class="d-flex justify-content-start">
                                                                <span>
                                                                    <img width="20" height="20"
                                                                        src="{{ Storage::url($categorie->small_icon) }}" />
                                                                    &nbsp;
                                                                </span>
                                                                <span>
                                                                    {{ $categorie->titre }}
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <span>
                                                                    @if ($categorie->luxury == 1)
                                                                        <span class="color small">
                                                                            <b>
                                                                                <i class="bi bi-gem"></i>
                                                                                Luxury
                                                                            </b>
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
                                                                alt="{{ $selected_categorie->icon }}" class="w-100"
                                                                srcset="">
                                                        </div>
                                                        <div class="color p-1 ">
                                                            <a href="/shop?id_categorie={{ $selected_categorie->id }}"
                                                                class="color">
                                                                Tout les articles de
                                                                {{ $selected_categorie->titre }}
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
                                                                <button
                                                                    class="btn w-100 mb-1 d-flex btn-sm  justify-content-between"
                                                                    onclick="select_sous_categorie({{ $sous_categorie->id }})">
                                                                    <span>
                                                                        {{ $sous_categorie->titre }}
                                                                        @if ($selected_categorie->luxury == 1)
                                                                            <span class="color">
                                                                                <b>
                                                                                    <i class="bi bi-gem"></i>
                                                                                </b>
                                                                            </span>
                                                                        @endif
                                                                    </span>
                                                                    <span>
                                                                        {{ $sous_categorie->getPost->where('statut','vente')->count() }}
                                                                    </span>
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
                                        <div id="Selected_options" class="d-flex flex-wrap Selected_options"></div>
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
                                            <a href="#types" data-toggle="collapse" class="collapsed" aria-expanded="false"
                                                role="button">
                                                état
                                            </a>
                                        </h4>
                                    </div>
                                    <div class="widget-boxed-body collapse" id="types" data-parent="#types">
                                        <div class="side-list no-border">
                                            <!-- Single Filter Card -->
                                            <div>
                                                <div class="d-flex justify-content-start">
                                                    <input type="radio" name="etat" value="Neuf avec étiquettes"
                                                        onclick="choix_etat(this)">
                                                    <button type="button" class="btn-etat-shop cusor ">
                                                        Neuf avec étiquettes
                                                    </button>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    <input type="radio" name="etat" value="Neuf sans étiquettes"
                                                        onclick="choix_etat(this)">
                                                    <button type="button" class="btn-etat-shop cusor">
                                                        Neuf sans étiquettes
                                                    </button>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    <input type="radio" name="etat" value="Très bon état"
                                                        onclick="choix_etat(this)">
                                                    <button type="button" class="btn-etat-shop cusor">
                                                        Très bon état
                                                    </button>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    <input type="radio" name="etat" value="Bon état"
                                                        onclick="choix_etat(this)">
                                                    <button type="button" class="btn-etat-shop cusor">
                                                        Bon état
                                                    </button>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    <input type="radio" name="etat" value="Usé"
                                                        onclick="choix_etat(this)">
                                                    <button type="button" class="btn-etat-shop cusor">
                                                        Usé
                                                    </button>
                                                </div>
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
                                        <h4>
                                            <a href="#prixs" data-toggle="collapse" class="collapsed"
                                                aria-expanded="false" role="button">
                                                prix
                                            </a>
                                        </h4>
                                    </div>
                                    <div class="widget-boxed-body collapse" id="prixs" data-parent="#prixs">
                                        <div class="side-list no-border">
                                            <!-- Single Filter Card -->
                                            <div>
                                                <div class="d-flex justify-content-start">
                                                    <input type="radio" name="ordre_prix" value="Asc"
                                                        onclick="choix_ordre_prix('prix_asc')" id="prix_asc">
                                                    <span class="btn-etat-shop cusor">
                                                        &nbsp;
                                                        Ordre croissant
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    <input type="radio" name="ordre_prix" value="Desc"
                                                        onclick="choix_ordre_prix('prix_desc')" id="prix_desc">
                                                    <span class="btn-etat-shop cusor">
                                                        &nbsp;
                                                        Ordre d'ecroissant
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    <input type="radio" name="ordre_prix" value="Soldé"
                                                        onclick="choix_ordre_prix('Soldé')" id="solder">
                                                    <span class="btn-etat-shop cusor">
                                                        &nbsp;
                                                        Articles soldés
                                                    </span>
                                                </div>
                                                @if (!$selected_categorie)
                                                    <div class="d-flex justify-content-start">
                                                        <input type="checkbox" name="ordre_prix" value="Desc"
                                                            onclick="choix_ordre_prix('luxury')">
                                                        <span class="btn-etat-shop cusor color">
                                                            &nbsp;
                                                            Uniquement <b><i class="bi bi-gem"></i> Luxury </b>
                                                        </span>
                                                    </div>
                                                @endif
                                                @error('ordre')
                                                    <small class="form-text text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="col-xl-9 col-lg-8 col-md-12 col-sm-12">

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <div class=" mb-3 mfliud">
                                <div class="d-flex justify-content-between p-2 m-0">
                                    <div>
                                    </div>
                                    <div>
                                        <select name="filtre-ordre" id="filtre-ordre" class="form-control-sm">
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




        function renderPagination(data) {
            const paginationControls = $('#pagination-controls');
            paginationControls.empty();

            //lancer la pagination uniquement si on a minimun un article
            if (data.data.length > 0) {
                if (data.current_page > 1) {
                    paginationControls.append('<li data-page="' + (data.current_page - 1) + '">Précédent</li>');
                }

                for (let i = 1; i <= data.last_page; i++) {
                    const activeClass = data.current_page === i ? 'active' : '';
                    paginationControls.append('<li data-page="' + i + '" class="' + activeClass + '">' + i + '</li>');
                }

                if (data.current_page < data.last_page) {
                    paginationControls.append('<li data-page="' + (data.current_page + 1) + '">Suivant</li>');
                }
            }

        }



       

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
    </style>
@endsection
