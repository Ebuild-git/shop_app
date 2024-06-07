@extends('User.fixe')
@section('titre', 'Marketplace')
@section('body')


    <section class="middle" id="ancre">

        <div class="navbar container">
            <div class="subnav">
                <a href="/shop" class="subnavbtn cusor">
                    <div>
                        <img src="https://img.icons8.com/ios/100/008080/select-all.png" alt="i" class="icon"
                            srcset="">
                    </div>
                    <div class="titre">
                        Tous
                    </div>
                </a>
            </div>
            @foreach ($liste_categories as $cat)
                <div class="subnav">
                    <button class="subnavbtn cusor">
                        <div>
                            <img src="{{ Storage::url($cat->small_icon) }}" alt="i" class="icon" srcset="">
                        </div>
                        <div class="titre">
                            @if ($cat->luxury == true)
                                <i class="bi bi-gem color"></i>
                            @endif
                            {!! str_replace('&', '&<br>', $cat->titre) !!}
                        </div>
                        <i class="fa fa-caret-down"></i>
                    </button>
                    <div class="subnav-content p-2">
                        <div class="row">
                            <div class="col-sm-2">
                                <button class="button button-list text-left" type="button"
                                    onclick="select_categorie({{ $cat->id }})">
                                    Tout {{ $cat->titre }}
                                </button>
                            </div>
                            @foreach ($cat->getSousCategories as $item)
                                <div class="col-sm-2">
                                    <button type="button" class="p-1 button-list  text-left"
                                        onclick="select_sous_categorie({{ $item->id }})">
                                        {{ $item->titre }}
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <br><br>

        <div class="container">
            <div class="row">

                <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 p-xl-0">
                    <div class="search-sidebar sm-sidebar border">
                        <div class="search-sidebar-body">
                            <div>
                                <input type="text" class="form-control border-r key-input" id="key"
                                    value="{{ $key ?? '' }}" name="key" placeholder="Mot clé de recherche">
                            </div>
                            <!-- Single Option -->
                            <div class="single_search_boxed">
                                <div class="widget-boxed-header px-3">
                                    <h4 class="mt-3">Categories</h4>
                                </div>
                                <div class="widget-boxed-body">
                                    <div class="side-list no-border">
                                        <div class="filter-card" id="shop-categories">
                                            @if (!$selected_categorie)
                                                @foreach ($liste_categories as $categorie)
                                                    <!-- Single Filter Card categorie -->
                                                    <div class="single_filter_card my-auto" id="list-categorie"
                                                        onclick="select_categorie({{ $categorie->id }})">
                                                        <button class="d-flex justify-content-between btn w-100">
                                                            <div class="d-flex justify-content-start">
                                                                <span>
                                                                    <img width="20" height="20"
                                                                        src="{{ Storage::url($categorie->small_icon) }}" />
                                                                    &nbsp;
                                                                </span>
                                                                <span class="small">
                                                                    {{ $categorie->titre }}
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <span>
                                                                    @if ($categorie->luxury == 1)
                                                                        <span class="color small">
                                                                            <b>
                                                                                <i class="bi bi-gem"></i>
                                                                                {{--  Luxury --}}
                                                                            </b>
                                                                        </span>
                                                                        &nbsp;
                                                                    @endif
                                                                    <i class="accordion-indicator ti-angle-down"></i>
                                                                </span>
                                                            </div>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            @endif
                                            <div>
                                                @if ($selected_categorie)
                                                    <div class="card card-image-shop-categorie">
                                                        <img src="{{ Storage::url($selected_categorie->icon) }}"
                                                            alt="{{ $selected_categorie->icon }}" class="w-100"
                                                            srcset="">
                                                    </div>
                                                    <div class="color  strong">
                                                        {{ $selected_categorie->titre }}
                                                    </div>
                                                    @if ($selected_sous_categorie)
                                                    @else
                                                        <hr>
                                                        <div class="p-1">
                                                            @foreach ($selected_categorie->getSousCategories as $sous_categorie)
                                                                <button
                                                                    class="btn w-100 mb-1 d-flex btn-sm justify-content-between"
                                                                    onclick="select_sous_categorie({{ $sous_categorie->id }})">
                                                                    <span>
                                                                        {{ $sous_categorie->titre }}
                                                                    </span>
                                                                    <span>
                                                                        {{ $sous_categorie->getPost->count() }}
                                                                    </span>
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                            <div>
                                                @if ($selected_sous_categorie)
                                                    <hr>
                                                    @foreach ($selected_sous_categorie->proprietes as $id_propriete)
                                                        @php
                                                            $propriete = DB::table('proprietes')->find($id_propriete);
                                                        @endphp
                                                        @if ($propriete)
                                                            <b> {{ $propriete->nom }}</b> <br>
                                                            <div class="p-1">
                                                                @if ($propriete->options)
                                                                    @foreach (json_decode($propriete->options ?? []) as $option)
                                                                        <button class="btn btn-sm m-1">
                                                                            {{ $option }}
                                                                        </button>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        @endif
                                                    @endforeach

                                                @endif
                                            </div>
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
                                            <div class="d-flex justify-content-start">
                                                <input type="checkbox" name="etat" value="Neuf avec étiquettes"
                                                    onclick="choix_etat(this)">
                                                <button type="button" class="btn-etat-shop cusor ">
                                                    Neuf avec étiquettes
                                                </button>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <input type="checkbox" name="etat" value="Neuf sans étiquettes"
                                                    onclick="choix_etat(this)">
                                                <button type="button" class="btn-etat-shop cusor">
                                                    Neuf sans étiquettes
                                                </button>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <input type="checkbox" name="etat" value="Très bon état"
                                                    onclick="choix_etat(this)">
                                                <button type="button" class="btn-etat-shop cusor">
                                                    Très bon état
                                                </button>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <input type="checkbox" name="etat" value="Bon état"
                                                    onclick="choix_etat(this)">
                                                <button type="button" class="btn-etat-shop cusor">
                                                    Bon état
                                                </button>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <input type="checkbox" name="etat" value="Usé"
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
                                        <a href="#prixs" data-toggle="collapse" class="collapsed" aria-expanded="false"
                                            role="button">
                                            prix
                                        </a>
                                    </h4>
                                </div>
                                <div class="widget-boxed-body collapse" id="prixs" data-parent="#prixs">
                                    <div class="side-list no-border">
                                        <!-- Single Filter Card -->
                                        <div>
                                            <div class="d-flex justify-content-start">
                                                <input type="checkbox" name="ordre_prix" value="Asc"
                                                    onclick="choix_ordre_prix(this)">
                                                <span class="btn-etat-shop cusor">
                                                    &nbsp;
                                                    Moins couteux au plus couteux
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <input type="checkbox" name="ordre_prix" value="Desc"
                                                    onclick="choix_ordre_prix(this)">
                                                <span class="btn-etat-shop cusor">
                                                    &nbsp;
                                                    Plus couteux au moins couteux
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <input type="checkbox" name="ordre_prix" value="Soldé"
                                                    onclick="choix_ordre_prix(this)">
                                                <span class="btn-etat-shop cusor">
                                                    &nbsp;
                                                    Articles soldés
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                <input type="checkbox" name="ordre_prix" value="Desc"
                                                    onclick="check_luxury()">
                                                <span class="btn-etat-shop cusor color">
                                                    &nbsp;
                                                    Uniquement <b><i class="bi bi-gem"></i> Luxury </b>
                                                </span>
                                            </div>
                                            @error('ordre')
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
                                                    <ul class="cusor">
                                                        @foreach ($regions as $region)
                                                            <li class="d-flex justify-content-between btn-etat-shop cusor">
                                                                <div class="d-flex justify-content-start">
                                                                    <input type="checkbox" name="region"
                                                                        value="{{ $region->id }}"
                                                                        onclick="select_region(this)">
                                                                    <span>
                                                                        &nbsp; {{ $region->nom }}
                                                                    </span>
                                                                </div>
                                                                <span>
                                                                    {{ $region->getPost->count() }}
                                                                </span>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="/shop" class="btn btn-sm btn-dark btn-block" type="reset">
                                <i class="fas fa-sync-alt"></i>
                                Réinitialiser la recherche
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 col-lg-8 col-md-12 col-sm-12">

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <div class="border mb-3 mfliud">
                                <div class="d-flex justify-content-between p-2 m-0">
                                    <div>
                                        <h6 class="mb-0">
                                            <a href="/shop" class="color">
                                                Catégories
                                            </a>
                                            >
                                            @if ($selected_categorie)
                                                <a href="shop?id_categorie={{ $selected_categorie->id }}" class="color">
                                                    {{ $selected_categorie->titre }}
                                                </a>
                                            @endif
                                            @if ($selected_sous_categorie)
                                                >
                                                <span class="text-back">
                                                    {{ $selected_sous_categorie->titre }}
                                                </span>
                                            @endif
                                        </h6>
                                    </div>

                                    <div>
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
        var key = $("#key").val();
        var categorie = {{ $selected_categorie->id ?? 'null' }};
        var sous_categorie = {{ $selected_sous_categorie->id ?? 'null' }};
        var region = "";
        var etat = "";
        var ordre_prix = "";
        var proprietes = "";

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

        function choix_ordre_prix(checkbox) {
            var checkboxes = document.getElementsByName('ordre_prix');
            checkboxes.forEach(function(cb) {
                if (cb !== checkbox) {
                    cb.checked = false;
                }
            });
            _ordre_prix = checkbox.value;
            if (_ordre_prix == ordre_prix) {
                ordre_prix = "";
            } else {
                ordre_prix = _ordre_prix;
            }
            fetchProducts();
        }

        function check_luxury() {
            check_luxury_only = "true";
            fetchProducts();
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
            fetchProducts();
        }

        function select_sous_categorie(id) {
            window.location.href = "{{ Request::fullUrl() }}&selected_sous_categorie=" + id;
            sous_categorie = id;
            fetchProducts();
        }

        function filtre_propriete(nom) {
            proprietes = nom;
            fetchProducts();
        }

        function select_categorie(id) {
            categorie = id;
            sous_categorie = "";
            window.location.href = "/shop?id_categorie=" + id;
            fetchProducts();
        }

        function fetchProducts(page = 1) {
            $("#loading").show("show");
            ancre();
            $.post(
                "/recherche?page=" + page, {
                    etat: etat,
                    key: key,
                    region: region,
                    ordre_prix: ordre_prix,
                    check_luxury: check_luxury_only,
                    categorie: categorie,
                    proprietes: proprietes,
                    sous_categorie: sous_categorie,
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
        });
    </script>


    <style>
        .navbar {}

        .navbar .icon {
            height: 30px;
        }

        .navbar .titre {
            font-size: 12px !important;
            height: 30px !important;
        }

        .navbar .fa-caret-down {
            display: none;
        }

        .navbar a {
            float: left;
            font-size: 16px;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .navbar .subnav:hover .fa-caret-down {
            display: block;
        }

        .subnav {
            overflow: hidden;
        }

        .subnav .subnavbtn {
            font-size: 16px;
            border: none;
            outline: none;
            background-color: inherit;
            font-family: inherit;
            margin: 0;
        }

        .navbar a:hover,
        .subnav:hover .subnavbtn {
            border-bottom: solid 2px #008080;
        }

        .subnav-content {
            display: none;
            position: absolute;
            left: 0;
            background-color: #008080;
            width: 100%;
            z-index: 1;
        }

        .subnav-content a {
            color: white;
            font-size: 12px;
            text-decoration: none;
        }

        .subnav-content a:hover {
            color: rgb(255, 255, 255);
            font-weight: bold;
        }

        .subnav-content button {
            background-color: unset !important;
            color: white !important;
            border: none !important;
        }

        .subnav-content button:hover {
            border-radius: 5px !important;
            background-color: white !important;
            color: #008080 !important;
            font-weight: bold !important;
            cursor: pointer;
        }

        .subnav:hover .subnav-content {
            display: block;
        }

        .list-proprietes:hover .attribut {
            display: block !important;
            transition: 0.5s !important;
        }

        .list-proprietes {
            transition: 0.5s !important;
        }

        .button-list {
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
        }

        .card-hover-prroposition:hover {
            background-color: #018d8d;
            color: white !important;
            font-weight: bold;
            cursor: pointer;
        }

        .card-hover-titre {
            background-color: #f33066 !important;
            color: white !important;
        }

        .middle {
            padding-top: 0px !important;
        }

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

        .pagination .active {
            font-weight: bold;
            background-color: #008080;
            color: white;
        }
    </style>
@endsection
