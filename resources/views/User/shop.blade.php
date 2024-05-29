@extends('User.fixe')
@section('titre', 'Marketplace')
@section('content')
@section('body')

    <style>
        .navbar {}

        .navbar .icon {
            height: 30px;
        }

        .navbar .titre {
            font-size: 12px !important;
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
    </style>







    <section class="middle">

        <style>
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
        </style>




        <div class="navbar container">
            <div class="subnav">
                <a href="/shop" class="subnavbtn cusor">
                    <div>
                        <img src="https://img.icons8.com/ios/100/008080/select-all.png" alt="i" class="icon"
                            srcset="">
                    </div>
                    <span class="titre">
                        Tous
                    </span>
                </a>
            </div>
            @foreach ($liste_categories as $cat)
                <div class="subnav">
                    <button class="subnavbtn cusor">
                        <div>
                            <img src="{{ Storage::url($cat->small_icon) }}" alt="i" class="icon" srcset="">
                        </div>
                        <span class="titre">
                            @if ($cat->luxury == true)
                                <i class="bi bi-gem color"></i>
                            @endif
                            {!! str_replace('&', '&<br>', $cat->titre) !!}
                        </span>
                        <i class="fa fa-caret-down"></i>
                    </button>
                    <div class="subnav-content p-2">
                        <div class="row">
                            <div class="col-sm-2">
                                <button class="button w-100 text-left" type="button"
                                    onclick="select_categorie({{ $cat->id }})">
                                    {{ Str::limit('Tout ' . $cat->titre, 18) }}
                                </button>
                            </div>
                            @foreach ($cat->getSousCategories as $item)
                                <div class="col-sm-2">
                                    <button type="button" class="p-1  w-100 text-left"
                                        onclick="select_sous_categorie({{ $item->id }})">
                                        {{ Str::limit($item->titre, 18) }}
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
                            <form wire:submit="filtrer">
                                <div>
                                    <input type="text" class="form-control" id="key" name="key"
                                        placeholder="Mot clé de recherche">
                                </div>

                                <!-- Single Option -->
                                <div class="single_search_boxed">
                                    <div class="widget-boxed-header px-3">
                                        <h4 class="mt-3">Categories</h4>
                                    </div>
                                    <div class="widget-boxed-body">
                                        <div class="side-list no-border">
                                            <div class="filter-card" id="shop-categories">
                                                <div class="single_filter_card cusor color" onclick="check_luxury()">
                                                    <b>
                                                        Uniquement Luxury <i class="bi bi-gem"></i>
                                                    </b>
                                                </div>
                                                @forelse ($liste_categories as $categorie)
                                                    <!-- Single Filter Card -->
                                                    <div class="single_filter_card">
                                                        <h5>
                                                            <a href="#cat-{{ $categorie->id }}" data-toggle="collapse"
                                                                class="collapsed d-flex justify-content-between"
                                                                aria-expanded="false" role="button">
                                                                {{ $categorie->titre }}

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
                                                                    <i class="accordion-indicator ti-angle-down"></i>
                                                                </span>

                                                            </a>
                                                        </h5>

                                                        <div class="collapse" id="cat-{{ $categorie->id }}"
                                                            data-parent="#shop-categories">
                                                            <div class="card-body">
                                                                <div class="inner_widget_link">
                                                                    <ul>
                                                                        <li class="d-flex justify-content-between">
                                                                            <button class="btn-btn-shop-style"
                                                                                type="button"
                                                                                onclick="select_categorie({{ $categorie->id }})">
                                                                                Tout - {{ $categorie->titre }}
                                                                            </button>
                                                                        </li>
                                                                        @foreach ($categorie->getSousCategories as $SousCategorie)
                                                                            <li class="d-flex justify-content-between">
                                                                                <button class="btn-btn-shop-style"
                                                                                    type="button"
                                                                                    onclick="select_sous_categorie({{ $SousCategorie->id }})">
                                                                                    {{ $SousCategorie->titre }}
                                                                                </button>
                                                                                <span>
                                                                                    {{ $SousCategorie->getPost->count() }}
                                                                                </span>
                                                                            </li>
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
                                                <div class="d-flex justify-content-start">
                                                    <input type="checkbox" wire:click="choix_etat('Neuf avec étiquettes')">
                                                    <button type="button" class="btn-etat-shop cusor ">
                                                        Neuf avec étiquettes
                                                    </button>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    <input type="checkbox" wire:click="choix_etat('Neuf sans étiquettes')">
                                                    <button type="button" class="btn-etat-shop cusor">
                                                        Neuf sans étiquettes
                                                    </button>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    <input type="checkbox" wire:click="choix_etat('Très bon état')">
                                                    <button type="button" class="btn-etat-shop cusor">
                                                        Très bon état
                                                    </button>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    <input type="checkbox" wire:click="choix_etat('Bon état')">
                                                    <button type="button" class="btn-etat-shop cusor">
                                                        Bon état
                                                    </button>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    <input type="checkbox" wire:click="choix_etat('Usé')">
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
                                        <h4><a href="#prixs" data-toggle="collapse" class="collapsed"
                                                aria-expanded="false" role="button">
                                                Ordre d'affichage des prix
                                            </a></h4>
                                    </div>
                                    <div class="widget-boxed-body collapse" id="prixs" data-parent="#prixs">
                                        <div class="side-list no-border">
                                            <!-- Single Filter Card -->
                                            <div>
                                                <button type="button" class="btn-etat-shop cusor"
                                                    onclick="choix_ordre_prix('Desc')">
                                                    Moins couteux au plus couteux
                                                </button>
                                                <button type="button" class="btn-etat-shop cusor"
                                                    onclick="choix_ordre_prix('Asc')">
                                                    Plus couteux au moins couteux
                                                </button>
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
                                                                <li class="d-flex justify-content-between cusor">
                                                                    <button class="btn-btn-shop-style" type="button"
                                                                        onclick="select_region({{ $region->id }})">
                                                                        {{ $region->nom }}
                                                                    </button>
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
                            </form>
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
                                            <span id="total_show_post">0</span> sur <span id="total_post">0</span> éléments publiés.
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
                    {{-- @if ($proprietes_sous_cat)
                        <div class="card p-2 mb-3">
                            <p>
                                Veuillez choisir les propriétés pour une recherche plus exact.
                            </p>
                            <div class="d-flex align-content-start flex-wrap ">
                                @foreach ($proprietes_sous_cat as $key => $item)
                                    <div class="d-flex align-content-start flex-wrap list-proprietes">
                                        <button type="button" class="card p-1 m-1 card-hover-titre cusor"
                                            data-key="{{ $item['nom'] }}" onclick="show_attribut({{ $key }})"
                                            wire:click="set_key('{{ $item['nom'] }}')">
                                            {{ $item['nom'] }}
                                        </button>
                                        @foreach ($item['options'] ?? [] as $option)
                                            <div class="card p-1 m-1 card-hover-prroposition cusor d-none attribut attribut-{{ $item['nom'] }}"
                                                wire:click="set_key('{{ $option['nom'] }}')">
                                                {{ $option['nom'] }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif --}}


                    <!-- row -->
                    <div class="row align-items-center rows-products">
                    </div>
                </div>

            </div>
        </div>

    </section>

    <script>
        //initialisation
        var check_luxury_only = false;
        var key = "";
        var categorie = "";
        var sous_categorie = "";
        var region = "";
        var ordre_prix ="";


        $(document).ready(function() {
            // Faire la requête initiale au chargement de la page
            fetchProducts();

            // Ajouter un écouteur d'événements pour la saisie dans le champ de recherche
            $('#key').on('input', function() {
                key = $('#key').val();
                fetchProducts();
            });
        });

        function choix_ordre_prix(ordre){
            ordre_prix = ordre;
            fetchProducts();
        }

        function check_luxury() {
            check_luxury_only = "true";
            fetchProducts();
        }

        function select_region(id) {
            region = id;
            fetchProducts();
        }

        function select_sous_categorie(id) {
            sous_categorie = id;
            fetchProducts();
        }

        function select_categorie(id) {
            categorie = id;
            fetchProducts();
        }

        function fetchProducts() {
            $.get(
                "/recherche", {
                    key: key,
                    region: region,
                    ordre_prix:ordre_prix,
                    check_luxury: check_luxury_only,
                    categorie: categorie,
                    sous_categorie: sous_categorie,
                }, // Passer la valeur de la recherche comme paramètre
                function(data, status) {
                    if (status === "success") {
                        $(".rows-products").empty();
                        $(".rows-products").html(data.html);
                        $("#total_post").text(data.total);
                        $("#total_show_post").text(data.count_resultat);
                    }
                }
            );
        }
    </script>



@endsection
