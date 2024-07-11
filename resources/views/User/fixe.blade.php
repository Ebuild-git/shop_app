@php
    $configurations = DB::table('configurations')->first();
    $categories = \App\Models\categories::orderBy('order', 'ASC')->get(['id', 'titre', 'luxury', 'pourcentage_gain']);
@endphp


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="author" content="Themezhub" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('titre') | {{ config('app.name', 'Shopin') }}</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="{{ asset('style.css?v=') . time() }}">
    <script src="{{ asset('js/Cart.js?v=') . time() }}"></script>

    <!-- Custom CSS -->

    <link rel="shortcut icon" href="/icons/icone.png" type="image/x-icon">

    @yield('head')
    @livewireStyles

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
        integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="{{ asset('assets/css/styles.css?v=') . time() }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @auth
        <script src="{{ asset('js/Auth-Cart.js?v=') . time() }}"></script>
    @endauth
</head>

<body class="custom-scrollbar">

    <button class="close-modal-preview" id="close-modal-preview">
        <img width="40" height="40"
            src="https://img.icons8.com/external-creatype-outline-colourcreatype/40/FFFFFF/external-close-essential-ui-v4-creatype-outline-colourcreatype.png"
            alt="external-close-essential-ui-v4-creatype-outline-colourcreatype" />
    </button>
    <script>
        function showPassword(id) {
            var x = document.getElementById("password-" + id);
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
        document.addEventListener('livewire:init', () => {
            Livewire.on('alert', (parametres) => {
                console.log(parametres);
                const message = parametres[0].message;
                const type = parametres[0].type;


                Swal.fire({
                    position: "center",
                    icon: type,
                    title: message,
                    showConfirmButton: false,
                    timer: 2000,
                    customClass: "swal-wide",
                });
            });
        });
        document.addEventListener('livewire:init', () => {
            Livewire.on('alert2', (parametres) => {
                console.log(parametres);
                const message = parametres[0].message;
                const type = parametres[0].type;
                const time = parametres[0].time;


                Swal.fire({
                    position: "center",
                    icon: type,
                    title: message,
                    showConfirmButton: false,
                    timer: time,
                    customClass: "swal-wide",
                });
            });
        });


        //ouverture du modal pour la previsualisation du post
        document.addEventListener('livewire:init', () => {
            Livewire.on('openmodalpreview', (data) => {
                console.log(data);
                $("#modal_motifs_preview_post").modal("toggle");
            });
        });


        //formatage du numero de telephone
        function formatTelephone(input) {
            var phoneNumber = input.value.replace(/\D/g, ''); // Supprime tous les caractères non numériques
            // Crée des groupes de deux chiffres séparés par des espaces
            var formattedPhoneNumber = '';
            for (var i = 0; i < phoneNumber.length; i++) {
                formattedPhoneNumber += phoneNumber[i];
                if ((i + 1) % 2 === 0 && i < phoneNumber.length - 1) {
                    formattedPhoneNumber += ' ';
                }
            }
            input.value = formattedPhoneNumber;
        }
    </script>
    @auth
        <style>
            .comment-position-top {
                position: absolute;
                right: 150px;
                top: -63px;
            }
        </style>
    @endauth
    @guest
        <style>
            .comment-position-top {
                position: absolute;
                right: 0px;
                top: -63px;
            }
        </style>
    @endguest
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader"></div>

    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Top header  -->
        <!-- ============================================================== -->
        <div class="py-2 bg-dark">
            <div class="container">
                <div class="row">

                    <!-- Right Menu -->
                    <div class="col-xl-4 col-lg-4 col-md-5 col-sm-12 ">
                        <!-- Choose Language -->

                        <div class="language-selector-wrapper dropdown js-dropdown  mr-3">
                            <a class="popup-title" href="javascript:void(0)" data-toggle="dropdown" title="Language"
                                aria-label="Language dropdown">
                                <span class="hidden-xl-down medium text-light">Language:</span>
                                <span class="iso_code medium text-light">Français</span>
                                <i class="fa fa-angle-down medium text-light"></i>
                            </a>
                            <ul class="dropdown-menu popup-content link">
                                <li><a href="javascript:void(0);" class="dropdown-item medium text-medium"><img
                                            src="/assets/img/2.jpg" alt="fr" width="16"
                                            height="11" /><span>Français</span></a></li>
                                <li class="current"><a href="javascript:void(0);"
                                        class="dropdown-item medium text-medium"><img src="/assets/img/1.jpg"
                                            alt="en" width="16" height="11" /><span>English</span></a></li>
                        </div>



                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-5 col-sm-12 hide-ipad">
                        Appelez le:
                        <a href="callto:{{ $configurations->phone_number ?? '' }}" class="medium text-light">
                            {{ $configurations->phone_number ?? '' }}
                        </a>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-5 col-sm-12 float-right d-flex justify-content-end">
                        <div class="top_first hide-ipad">

                            <a href="/about" style="color: white !important;padding-right: 17px">À propos</a>

                        </div>

                    </div>


                </div>
            </div>
        </div>

        <div class="container pt-2 pb-2">
            <div class="row">
                <div class="col-sm-2 col-4">
                    <a class="nav-brand" href="/">
                        <img src="/icons/logo.png" class="logo" alt="" />
                    </a>
                </div>
                <div class="col-sm-7 col-8">
                    <div class="row">
                        <div class="col-8 ">
                            <form action="/shop" method="get" class="position-relative">
                                @csrf
                                <input type="text" class="form-control sm input cusor border-r" name="key"
                                    placeholder="rechercher un article">
                                <button type="submit" class="span-icon-recherche cusor">
                                    <i class="bi bi-search"></i>
                                </button>
                            </form>
                        </div>

                        <div class="col-4" style="text-align: left !important;">
                            @auth
                                <a href="/publication">
                                @else
                                    <a href="#" data-toggle="modal" data-target="#login">
                                    @endauth
                                    <button class=" btn-publier-header cusor p-2 " type="button">
                                        <i class="lni lni-circle-plus"></i>
                                        <span class="hide-mobile-version">
                                            Publier un article
                                        </span>
                                    </button>
                                </a>
                        </div>

                    </div>
                </div>
                <div class="col-sm-3 mx-auto my-auto" style="text-align: right !important">
                    <div class="currency-selector dropdown js-dropdown ml-3">
                        <a href="javascript:void(0);" class="text-light medium text-capitalize" data-toggle="dropdown"
                            title="Language" aria-label="Language dropdown">
                            @auth
                                <span style="color: black;">
                                    {{ Auth::user()->username }}
                                    <i class="bi bi-caret-down"></i>
                                </span>
                                <i class="fa fa-angle-down medium text-light"></i>
                                <ul class="dropdown-menu popup-content p-3 ">
                                    <li>
                                        <a href="/mes-achats" class=" medium link-red text-medium">
                                            Mes achats
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/mes-publication?type=vente" class=" medium link-red text-medium">
                                            Mes ventes
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/mes-publication?type=annonce" class=" medium link-red text-medium">
                                            Mes annonces
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/user-notifications" class=" medium link-red text-medium">
                                            Notifications
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('liked') }}" class=" medium link-red text-medium">
                                            Mes coups de coeur
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('favoris') }}" class=" medium link-red text-medium">
                                            Mes favoris
                                        </a>
                                    </li>

                                    <li>
                                        <a href="/informations" class=" medium link-red text-medium">
                                            Mon compte
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/logout" class=" medium text-medium link-red">
                                            Déconnexion
                                        </a>
                                    </li>
                                </ul>
                            @endauth
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(window).scroll(function() {
                var elementToHide = $('.elementToHideBeforeScroll');
                var icons_position = $('#icons_position');
                var comment_position = $('#comment_position');
                var scrollPosition = $(window).scrollTop();

                if (scrollPosition === 0) {
                    elementToHide.addClass('d-none');
                    comment_position.addClass("comment-position").removeClass("comment-position-top");
                    icons_position.removeClass("comment-position").addClass("comment-position-top");
                } else {
                    icons_position.addClass("comment-position").removeClass("comment-position-top");
                    comment_position.removeClass("comment-position").addClass("comment-position-top");
                    elementToHide.removeClass('d-none');
                }
            });
            $(document).ready(function() {
                var inputField = $('#myInputRecherche');
                inputField.click(function() {
                    inputField.addClass('full-width');
                });
                inputField.blur(function() {
                    inputField.removeClass('full-width');
                });
            });
        </script>
        <!-- Start Navigation -->
        <div class="header header-light dark-text">
            <div class="container">
                <nav id="navigation" class="navigation navigation-landscape">
                    <div class="nav-header">

                        <div class="nav-toggle"></div>

                    </div>
                    <div class="nav-menus-wrapper" style="transition-property: none;">
                        <ul class="nav-menu text-uppercase">

                            <li class="elementToHideBeforeScroll d-none">
                                <a href="/">
                                    <img src="/icons/logo.png" class="logo" alt="" height="20" />
                                </a>
                            </li>
                            <li>
                                <a href="/" style="padding-left: 0px !important">Accueil</a>
                            </li>




                            <li>
                                <a href="/shop">CATÉGORIES</a>
                                <ul class="nav-dropdown-xxx nav-submenu-xxx p-0 d-none "
                                    style="width: 300px !important;direction: ltr !important">
                                    @forelse ($categories as $item)
                                        <li>
                                            <a href="/shop?categorie={{ $item->id }}"
                                                style="padding-top: 6px;padding-bottom: 6px">
                                                <div class="d-flex justify-content-between">
                                                    <span>
                                                        {{ $item->titre }}
                                                    </span>
                                                    <span class="small color">
                                                        @if ($item->luxury == 1)
                                                            Luxury
                                                            <i class="bi bi-gem" style="font-weight: 800;"></i>
                                                        @endif
                                                    </span>
                                                </div>
                                            </a>
                                            @if ($item->getSousCategories->count() > 0)
                                                <ul
                                                    class="nav-dropdown nav-submenu p-1 scrollbar-y-nav custom-scrollbar">
                                                    @foreach ($item->getSousCategories as $sous)
                                                        <li>
                                                            <a href="/shop?sous_categorie={{ $sous->id }}"
                                                                class="p-2 text-start">
                                                                {{ $sous->titre }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @empty
                                    @endforelse
                                </ul>
                            </li>
                            <li>
                                <a href="{{ Auth::check() ? route('shopiners') : '#' }}"
                                    @guest data-toggle="modal" data-target="#login" @endguest>
                                    Shop<span class="color strong">in</span>ers
                                </a>

                            </li>
                            <li>
                                <a href="{{ route('contact') }}">Contact</a>
                            </li>
                            <li class="elementToHideBeforeScroll hide-mobile-version d-none">
                                <div class="div-sroll-recherche">
                                    <form action="/shop" method="get" class="container-search"
                                        style="margin-right: 150px !important;">
                                        @csrf
                                        <div class="container-search">
                                            <input type="text" placeholder="Recherche..." name="key">
                                            <button type="submit" class="search-search">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                @auth
                                    <a href="/publication" class=" btn-publier-header cusor p-1 ">
                                    @else
                                        <a href="#" class=" btn-publier-header cusor p-1" data-toggle="modal"
                                            data-target="#login">
                                        @endauth
                                        <span class="color small">
                                            <i class="lni lni-circle-plus"></i>
                                            Publier
                                        </span>
                                    </a>
                            </li>
                            <li class="option-icon-header comment-position-top" id="icons_position">
                                @auth
                                    <a href="{{ route('historique') }}" class="ml-2 icon-icon-header"
                                        style="color: black !important;">
                                        <i class="bi lni bi-clock-history icon-icon-header"></i>
                                        <span class="hide-desktop">Historique</span>
                                    </a>
                                @endauth

                                <a href="#" onclick="openCart()" class="position-relative"
                                    style="color: black !important;">
                                    <i class="bi lni bi-bag icon-icon-header"></i>
                                    <span class="dn-counter bg-success-ps" id="CountPanier-value">0</span>
                                    <span class="hide-desktop">Panier</span>
                                </a>

                                @guest
                                    <a href="#" data-toggle="modal" data-target="#login" class="icon-icon-header"
                                        style="color: black !important;">
                                        <i class="bi lni  bi-person-circle icon-icon-header"></i>
                                        <span class="hide-desktop">Connexion</span>
                                    </a>
                                @endguest
                                @auth
                                    <a href="{{ route('user-notifications') }}" style="color: black !important;">
                                        <i class="lni bi bi-bell icon-icon-header"></i>
                                        <span class="dn-counter bg-success-ps" id="CountNotification-value">0</span>
                                        <span class="hide-desktop">Notifications</span>
                                    </a>
                                @endauth
                            </li>
                            <li class="text-capitalize comment-position" id="comment_position">
                                <a href="#">Comment ça marche?</a>
                                <ul class="nav-dropdown nav-submenu">
                                    <li>
                                        <a href="{{ route('how_sell') }}">
                                            Comment Vendre ?
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('how_buy') }}">
                                            Comment acheter?
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/conditions">
                                            Conditions générales
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" data-toggle="modal" data-target="#tarifaire">
                                            Nos Politiques Tarifaires
                                        </a>
                                        <ul class="nav-dropdown nav-submenu p-2 custom-scrollbar-left"
                                            style="left: -110% !important;">
                                            @foreach ($categories as $tarif)
                                                <li style="direction: ltr !important">
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            {{ $tarif->titre }}
                                                            <span class="small color">
                                                                @if ($tarif->luxury == 1)
                                                                    <b>
                                                                        <i class="bi bi-gem"
                                                                            style="font-weight: 800;">
                                                                        </i>
                                                                        Luxury
                                                                    </b>
                                                                @endif
                                                        </div>
                                                        <div>
                                                            <span>
                                                                {{ intval($tarif->pourcentage_gain) }} %
                                                            </span>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                            </li>

                        </ul>


                    </div>
                </nav>
            </div>
        </div>
        <!-- End Navigation -->
        <div class="clearfix"></div>
        <!-- ============================================================== -->
        <!-- Top header  -->
        <!-- ============================================================== -->

        @yield('body')



        <!-- ============================ Footer Start ================================== -->
        <footer class="light-footer">
            <br>
            <div class="p-2 text-center">
                <h3>
                    Pourquoi choisir SHOP<span class="color">IN</span> ?
                </h3>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="card-footer-option p-3 no-border">
                            <div class="d-flex bd-highlight ">
                                <div class="flex-fill pr-2">
                                    <img width="40" height="40"
                                        src="https://img.icons8.com/ios-filled/50/1A1A1A/security-shield-green.png"
                                        alt="security-shield-green" />
                                </div>
                                <div class="flex-fill">
                                    <h4 class="color">Protection et Sécurité</h4>
                                    <p>
                                        En cas de non-conformité de
                                        l'article, soyez assuré que nous
                                        garantissons le remboursement
                                        total, avec une prise en charge
                                        complète des frais de retour !
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card-footer-option p-3 no-border">
                            <div class="d-flex bd-highlight ">
                                <div class="flex-fill pr-2">
                                    <img width="40" height="40"
                                        src="https://img.icons8.com/ios/50/1A1A1A/lol--v1.png" alt="lol--v1" />
                                </div>
                                <div class="flex-fill">
                                    <h4 class="color">Expérience agréable!</h4>
                                    <p>
                                        Acheter ou vendre, votre
                                        expérience sur SHOPIN sera
                                        toujours marquée par un
                                        service exceptionnel, une
                                        simplicité inégalée et une
                                        satisfaction garantie.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card-footer-option p-3 no-border">
                            <div class="d-flex bd-highlight ">
                                <div class="flex-fill pr-2">
                                    <img width="40" height="40"
                                        src="https://img.icons8.com/ios-filled/50/1A1A1A/delivery--v1.png"
                                        alt="delivery--v1" />
                                </div>
                                <div class="flex-fill">
                                    <h4 class="color">Livraison porte à porte</h4>
                                    <p>
                                        Un livreur se rendra
                                        directement à votre porte
                                        pour récupérer ou livrer vos
                                        articles. Fini le casse-tête de
                                        la vente à distance! <br><br>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br><br>
            </div>

        </footer>
        <footer class="light-footer" style="background-">
            <footer class="light-footer" style="background-color: #edeff1 !important;">
                <div class="footer-middle">
                    <div class="container">
                        <div class="row">

                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                <div class="footer_widget">
                                    <img src="/icons/logo.png" class="img-footer small mb-2" alt="" />

                                    <div class="address mt-3">
                                        {{ $configurations->adresse ?? '' }}
                                    </div>
                                    <div class="address mt-3">
                                        {{ $configurations->phone_number ?? '' }}<br>{{ $configurations->email ?? '' }}
                                    </div>
                                    <div class="address mt-3">
                                        <ul class="list-inline">
                                            @if ($configurations->facebook)
                                                <li class="list-inline-item"><a
                                                        href="{{ $configurations->facebook }}"><i
                                                            class="lni lni-facebook-filled"></i></a></li>
                                            @endif
                                            @if ($configurations->tiktok)
                                                <li class="list-inline-item"><a
                                                        href="{{ $configurations->tiktok }}"><i
                                                            class="lni lni-tiktok-filled"></i></a></li>
                                            @endif
                                            @if ($configurations->instagram)
                                                <li class="list-inline-item"><a
                                                        href="{{ $configurations->instagram }}"><i
                                                            class="lni lni-instagram-filled"></i></a></li>
                                            @endif
                                            @if ($configurations->linkedin)
                                                <li class="list-inline-item"><a
                                                        href="{{ $configurations->linkedin }}"><i
                                                            class="lni lni-linkedin-original"></i></a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                <div class="footer_widget">
                                    <h4 class="widget_title">Générales</h4>
                                    <ul class="footer-menu">
                                        <li><a href="/contact">Contactez-nous</a></li>
                                        <li><a href="#">Page FAQs</a></li>
                                        @guest
                                            <li><a href="/inscription">Abonnez-vous</a></li>
                                            <li><a href="/connexion">Connexion</a></li>
                                        @endguest
                                    </ul>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                <div class="footer_widget">
                                    <h4 class="widget_title">Informations</h4>
                                    <ul class="footer-menu">
                                        <li><a href="/about">À propos</a></li>
                                        <li><a href="/how_sell">Comment Vendre?</a></li>
                                        <li><a href="/how_buy">Comment Acheter?</a></li>
                                        <li><a href="/conditions">Conditions Générales</a></li>
                                        <li><a href="#" data-toggle="modal" data-target="#tarifaire">Politiques
                                                Tarrifaires</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                <div class="footer_widget">
                                    <h4 class="widget_title">Nos Partenaires</h4>
                                    <div class="address mt-3">
                                        <div class="container-logo-home">
                                            <div class="logo-list-logo-home">
                                                @foreach (json_decode($configurations->partenaires) ?? [] as $item)
                                                    <img src="{{ Storage::url($item) }}" alt=""
                                                        srcset="">
                                                @endforeach
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </footer>
            <!-- ============================ Footer End ================================== -->

    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->






    <!-- Cart -->
    <div class="w3-ch-sideBar w3-bar-block w3-card-2 w3-animate-right" style="display:none;right:0;" id="Cart">
        <div class="rightMenu-scroll">
            <div class="d-flex align-items-center justify-content-between slide-head py-3 px-3">
                <h4 class="cart_heading fs-md ft-medium mb-0">
                    Mon panier
                </h4>
                <button onclick="closeCart()" class="close_slide">
                    <i class="ti-close"></i>
                </button>
            </div>
            <div class="right-ch-sideBar">
                @auth
                    <div class="container">
                        <div class="alert bg-red" style="display: none;" id="div-success-add-card">
                        </div>
                    </div>
                    <div class="cart_select_items py-2" id="cart_select_items">
                        <div>
                            <div id="Contenu-panier">
                            </div>
                            <div class="d-flex align-items-center justify-content-between br-top br-bottom px-3 py-3 gray">
                                <div>
                                    <span>
                                        <span class="CountPanier-value strong">-</span>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-0">
                                        Sous-total du panier
                                    </h6>
                                    <h6 class=" color">
                                        <span id="montant-panier" class="strong">0</span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="cart_action px-3 py-3">
                            <div class="form-group">
                                <a href="/checkout" class="btn d-block full-width btn-dark">
                                    Voir le panier
                                </a>
                            </div>
                        </div>
                    </div>
                @endauth
                <div class="text-center p-3" id="empty-card-div">
                    <b>
                        Aucun article dans votre panier !
                    </b>
                </div>
            </div>
        </div>
    </div>



    <a id="back2Top" class="top-scroll" title="Back to top" href="#">
        <i class="ti-arrow-up"></i>
    </a>



    @if (Route::currentRouteName() != 'connexion')
        <!-- Log In Modal -->
        <div class="modal fade" id="login" tabindex="1" role="dialog" aria-labelledby="loginmodal"
            aria-hidden="true">
            <div class="modal-dialog modal-xl login-pop-form" role="document">
                <div class="modal-content" id="loginmodal">
                    <div class="modal-headers">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="ti-close"></span>
                        </button>
                    </div>
                    <div class="modal-body p-5">
                        <div class="text-center mb-4">
                            <h2 class="m-0 ft-regular">Connexion</h2>
                        </div>
                        @livewire('User.Connexion')
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal -->
    @endif



    <!-- Log In Modal -->
    <div class="modal fade" id="tarifaire" tabindex="1" role="dialog" aria-labelledby="loginmodal"
        aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body p-1 pt-3">
                    <div class="text-center mb-4">
                        <h5 class="m-0 ft-regular">
                            <img width="20" height="20"
                                src="https://img.icons8.com/ios/20/008080/ticket--v1.png" alt="ticket--v1" />
                            <b>
                                Nos Politiques Tarifaires
                            </b>
                        </h5>
                    </div>
                    <div class="p-2">
                        <table class="w-100">
                            @foreach ($categories as $tarif)
                                <tr>
                                    <td>
                                        <b> {{ $tarif->titre }}</b>
                                        <span class="small color">
                                            @if ($tarif->luxury == 1)
                                                <b>
                                                    <i class="bi bi-gem" style="font-weight: 800;"></i>
                                                    Luxury
                                                </b>
                                            @endif
                                        </span>
                                    </td>
                                    <td style="text-align: right !important;">
                                        {{ intval($tarif->pourcentage_gain) }} %
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <hr>
                        <p class="text-center">
                            Le montant de la comission est prélécé au moment du paiement par l'acheteur
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->


    <!-- Condition Modal -->
    <div class="modal fade" id="conditions" tabindex="-1" role="dialog" aria-labelledby="conditions"
        aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content" id="conditions">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>

                <div class="modal-body p-5 modal-dialog-scrollable" id="conditiondiv">
                    @include('User.composants.text-conditions')
                </div>
                <div class="p-2">
                    <div class="modal-footer">
                        <button type="button" class="btn  bg-dark  btn-sm" id="agree_condition">
                            J'accepte les conditions
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->



    <div class="modal fade" id="CategoryPostsModal" tabindex="1" role="dialog" aria-labelledby="loginmodal"
        aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>

                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <h2 class=" h5">
                            Catégories vendus !
                        </h2>
                        <h4 class="h6 color">
                            Par : <span id="username_user_modal_categorie"> [ username ] </span>
                        </h4>
                    </div>
                    <hr>
                    <div>
                        <ul class="list-group list-group-flush text-left " id="catelist">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    @auth


   


        <!-- Modal pour voir la liste des motifs d'un post réfuser -->
        <div class="modal fade" id="modal_motifs_des_refus" tabindex="1" role="dialog" aria-labelledby="UpdatePrice"
            aria-hidden="true">
            <div class="modal-dialog modal-xl login-pop-form" role="document">
                <div class="modal-content" id="UpdatePrice">
                    <div class="modal-headers">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="ti-close"></span>
                        </button>
                    </div>
                    <div class="modal-body p-5">
                        <div class="text-center mb-4">
                            <h1 class="m-0 ft-regular h6">
                                <i class="bi bi-exclamation-octagon"></i>
                                Liste des motifs
                            </h1>
                            <span class="text-muted">
                            </span>
                        </div>
                        <div style="text-align: left;">
                            <div>
                                <table class="table" id="modal_motifs_des_refus-table">
                                    <thead>
                                        <tr>
                                            <th>Motif</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal pour voir la liste des motifs d'un post réfuser -->



        <!-- Condition Modal -->
        <div class="modal fade" id="first-login" tabindex="-1" role="dialog" aria-labelledby="first-login"
            aria-hidden="true">
            <div class="modal-dialog " role="document">
                <div class="modal-content" id="first-login">
                    <div class="modal-headers">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="ti-close"></span>
                        </button>
                    </div>

                    <div class="first-login ">
                        <div class="text-center">
                            <img width="100" height="100"
                                src="https://img.icons8.com/carbon-copy/100/018d8d/camera--v1.png" alt="camera--v1" />
                            <h5 class="color">
                                <b>
                                    Bienvennue ,
                                    {{ Auth::user()->username }}
                                </b>
                            </h5>
                        </div>
                        <p class="p-3">
                            Nous vous informons que votre photo de profil sera soumise à un processus de validation, qui
                            prendra jusqu'à un maximum de 24 heures avant d'être approuvée.
                            <br><br>
                            Nous vous remercions pour votre
                            patience et votre compréhension.
                        </p>

                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal -->



        @yield('modal')



        @if (Auth::user()->first_login_at == null && is_null(Auth::user()->photo_verified_at))
            <script>
                $(document).ready(function() {
                    $('#first-login').modal('show');
                });
            </script>
            @php
                Auth::user()->markFirstLogin();
            @endphp
        @endif
    @endauth

    <script>
        //verifier si les conditons on ete accepter ou page
        $(document).ready(function() {
            var conditionsAccepted = localStorage.getItem('conditionsAccepted');
            if (!conditionsAccepted) {
                $('#conditions').modal('show');
                $("#agree_condition").click(function() {
                    localStorage.setItem('conditionsAccepted', true);
                    $('#conditions').modal('hide');
                });
            }
        });
        document.getElementById('conditiondiv').addEventListener('scroll', function() {
            document.getElementById('agree_condition').disabled = false;
        });


        // gerer l'ouverture du modal pour l'affichage des categories publier par les shopinners
        function ShowPostsCatgorie(id) {
            //open CatégoriesPost modal
            $.ajax({
                url: "/category/post_user",
                data: {
                    id_user: id
                },
                type: "GET",
                success: function(response) {
                    console.log("Success");
                    $('.catgoryPosts').empty().html(response.view);
                    $('#username_user_modal_categorie').html(response.username)
                    $('#CategoryPostsModal').modal('toggle');
                },
                error: function() {
                    alert("Error while loading posts of this category !");
                }
            });
        }
    </script>

    <!-- end Condition Modal -->



    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/popper.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/ion.rangeSlider.min.js"></script>
    <script src="/assets/js/slick.js"></script>
    <script src="/assets/js/slider-bg.js"></script>
    <script src="/assets/js/lightbox.js"></script>
    <script src="/assets/js/smoothproducts.js"></script>
    <script src="/assets/js/snackbar.min.js"></script>
    <script src="/assets/js/jQuery.style.switcher.js"></script>
    @livewireScripts
    <script src="/assets/js/custom.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->



    <script>
        function openWishlist() {
            document.getElementById("Wishlist").style.display = "block";
        }

        function closeWishlist() {
            document.getElementById("Wishlist").style.display = "none";
        }
    </script>

    <script>
        function openCart() {
            document.getElementById("Cart").style.display = "block";
        }

        function closeCart() {
            document.getElementById("Cart").style.display = "none";
        }
    </script>
    @yield('script')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


</body>

</html>
