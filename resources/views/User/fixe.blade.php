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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @auth
        <script src="{{ asset('js/Auth-Cart.js?v=') . time() }}"></script>
    @endauth

    <style>
    #comment_position {
        position: absolute;
        z-index: 150;
    }

    #comment_position .nav-dropdown {
        position: absolute;
        z-index: 150;
    }
    .left-aligned {
        right: 100%;
        left: auto !important;
        top: 0;
    }
    .tarif-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.25rem 1rem; /* Reduced padding */
        background-color: white;
        color: #565656;
        font-size: bold;
        margin-bottom: 0.25rem; /* Reduced margin */
    }
    .nav-dropdown.nav-submenu.left-aligned {
        width: 400px;
        padding: 0.5rem; /* Reduced padding */
        background-color: #f8f9fa;
    }
    .tarif-title {
        display: flex;
        align-items: center;
        font-weight: 500;
    }

    .luxury-icon, .luxury-text {
        color: #008080;
        font-weight: 800;
        margin-left: 0.5rem;
    }

    .tarif-percentage {
        font-weight: small;
        color: #808080;
        margin-left: 1rem; /* Increased space between percentage and other items */
    }

    .tarif-item:hover {
        background-color: #f8f9fa;
    }

    .tarif-separator {
        border-top: 1px solid #f0f0f0;
        margin: 4px 0; /* Reduced margin */
    }

    .tarif-note {
        padding: 0.5rem 1rem; /* Reduced padding */
        font-size: 0.85rem;
        color: #808080;
        text-align: center;
        white-space: normal;
    }

    #agreeConditionButton {
    background-color: #008080; /* Green color to attract attention */
    color: white; /* White text for good contrast */
    padding: 10px 20px; /* Increase the padding for a larger button */
    font-size: 1.1rem; /* Slightly larger text */
    border: none; /* Remove default borders */
    border-radius: 5px; /* Rounded corners for a modern look */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Subtle shadow to lift the button */
    transition: background-color 0.3s ease, transform 0.2s ease;
    }

    #agreeConditionButton:hover {
        background-color: #008080;
        transform: scale(1.05);
    }

    #agreeConditionButton:active {
        transform: scale(0.98);
    }

    #agreeConditionButton::before {
        content: '\2714';
        margin-right: 8px;
        font-size: 1.2rem;
    }

    #agreeConditionButton.pulsing {
        animation: pulse 1.5s infinite;
    }

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 #008080;
    }
    70% {
        box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
    }
}

@media (max-width: 768px) {
    .logo-container {
        display: flex;
        justify-content: center;
        text-align: center;
    }

    .logo-container img {
        margin: 0 auto;
    }

    .nav-brand {
        display: block;
    }

    .col-sm-2,
    .col-sm-7,
    .col-sm-3 {
        flex: 100%;
        max-width: 100%;
    }

    .logo {
        margin-bottom: 10px;
    }

    .form-control {
        width: 100%;
        margin-bottom: 10px;
        margin-top: 5px;
    }

    .btn-publier-header {
        width: 100%;
        margin-bottom: 10px;
        margin-top: 5px;
    }

    .dropdown-menu {
        position: absolute;
        right: 0;
        left: 0;
        width: 50%;
    }
}


</style>
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
    <script>
        window.onload = function() {
    document.getElementById('agreeConditionButton').classList.add('pulsing');
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

        <div class="container desktop-container pt-2 pb-2">
            <div class="row">
                <div class="col-sm-2 col-4 logo-container">
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
                                        <a href="/mes-publication?type=annonce" class=" medium link-red text-medium">
                                            Mes annonces
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/mes-publication?type=vente" class=" medium link-red text-medium">
                                            Mes ventes
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/mes-achats" class=" medium link-red text-medium">
                                            Mes achats
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/checkout" class=" medium link-red text-medium">
                                            Mon panier
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
                                        <a href="/user-notifications" class=" medium link-red text-medium">
                                            Notifications
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/informations" class=" medium link-red text-medium">
                                            Mon compte
                                        </a>
                                    </li>
                                    @livewire('User.ModeToggle')
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
<!-- Mobile Container -->
<div class="container mobile-container pt-2 pb-2">
    <div class="row align-items-center mobile-only-header">
        <!-- Navigation and Toggle Button -->
        <div class="col-12 d-flex justify-content-between align-items-center">
            <nav id="sidebar-navigation">
                <div class="nav-toggle">&#9776;</div>
                <div class="sidebar-wrapper">
                    <div class="close-menu">×</div>
                    <ul class="text-uppercase-mobile">
                        <li><a href="/about" style="padding-left: 0px !important">À propos</a></li>
                        <li><a href="/" style="padding-left: 0px !important">Accueil</a></li>
                        <li>
                            <a href="{{ Auth::check() ? route('shopiners') : '#' }}" @guest data-toggle="modal" data-target="#login" @endguest>
                                Shop<span class="color strong">in</span>ers
                            </a>
                        </li>
                        <li>
                            <a href="/shop">Catégories</a>
                            <ul class="categories-list p-0">
                                @forelse ($categories as $item)
                                    <li class="category-item">
                                        {{-- <a href="/shop?categorie={{ $item->id }}" class="category-link"> --}}
                                            <a href="javascript:void(0);" class="category-link" onclick="select_categorie({{ $item->id }})">
                                            <div class="d-flex align-items-center">
                                                <span>{{ $item->titre }}</span>
                                                <span class="small color">
                                                    @if ($item->luxury == 1)
                                                        <i class="bi bi-gem luxury-icon"></i>
                                                    @endif
                                                </span>
                                            </div>
                                        </a>
                                    </li>
                                @empty
                                @endforelse
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>


            <a class="nav-brand mobile-logo" href="/">
                <img src="/icons/logo.png" class="logo" alt="SHOPIN" />
            </a>
            <div class="d-flex align-items-center mobile-only-icons">
                @auth
                <a href="{{ route('historique',['type'=>'achats']) }}" class="ml-2 icon-icon-header"
                    style="color: black !important;">
                    <i class="bi lni bi-clock-history icon-icon-header"></i>
                </a>
                @endauth
                <a href="#" onclick="openCart()" class="position-relative mobile-panier" style="color: black !important; margin-left: 10px;">
                    <i class="bi lni bi-bag icon-icon-header"></i>
                    <span class="dn-counter bg-success-ps" id="CountPanier-value-mobile">0</span>
                </a>
                @guest
                <a href="#" data-toggle="modal" data-target="#login" class="icon-icon-header mobile-connexion" style="color: black !important; margin-left: 10px;">
                    <i class="bi lni bi-person-circle icon-icon-header"></i>
                </a>
                @endguest
                @auth
                <a href="{{ route('user-notifications') }}" class="mobile-notifications" style="color: black !important; margin-left: 10px;">
                    <i class="lni bi bi-bell icon-icon-header"></i>
                    <span class="dn-counter bg-success-ps" id="CountNotification-value-mobile">0</span>
                </a>
                @endauth
                <div class="col-sm-3 mx-auto my-auto text-right">
                    <div class="currency-selector dropdown js-dropdown ml-3 d-flex align-items-center">
                        <a href="javascript:void(0);" class="text-light medium text-capitalize d-flex align-items-center" data-toggle="dropdown"
                            title="Language" aria-label="Language dropdown">
                            @auth
                            {{-- <span class="d-flex align-items-end" style="color: black; font-size: 10px;">
                                <i class="bi bi-person user-emoji" role="img" aria-label="User"></i> <!-- Bootstrap user icon -->
                                <i class="bi bi-caret-down ml-1"></i>
                            </span> --}}
                            <span class="d-flex align-items-end">
                                <i class="fas fa-user user-emoji" role="img" aria-label="User" onclick="toggleActive(this)"></i>
                            </span>
                            @endauth
                        </a>
                        <ul class="dropdown-menu popup-content">
                            <li>
                                <a href="/mes-publication?type=annonce" class="medium link-red text-medium">
                                    Mes annonces
                                </a>
                            </li>
                            <li>
                                <a href="/mes-publication?type=vente" class="medium link-red text-medium">
                                    Mes ventes
                                </a>
                            </li>
                            <li>
                                <a href="/mes-achats" class="medium link-red text-medium">
                                    Mes achats
                                </a>
                            </li>
                            <li>
                                <a href="/checkout" class="medium link-red text-medium">
                                    Mon panier
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('liked') }}" class="medium link-red text-medium">
                                    Mes coups de coeur
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('favoris') }}" class="medium link-red text-medium">
                                    Mes favoris
                                </a>
                            </li>
                            <li>
                                <a href="/user-notifications" class="medium link-red text-medium">
                                    Notifications
                                </a>
                            </li>
                            <li>
                                <a href="/informations" class="medium link-red text-medium">
                                    Mon compte
                                </a>
                            </li>
                            @livewire('User.ModeToggle')
                            <li>
                                <a href="/logout" class="medium text-medium link-red">
                                    Déconnexion
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>

        <!-- Search and Publish Section -->
        <div class="col-12 d-flex justify-content-between align-items-center mt-3 mobile-search-publish">
            <form action="/shop" method="get" class="position-relative mobile-search-form">
                @csrf
                <div class="search-container">
                    <input type="text" class="form-control sm input cusor border-r mobile-search-input" name="key" placeholder="rechercher un article">
                    <button type="submit" class="search-button cusor mobile-search-btn-mobile">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            <div class="ml-3 mobile-publish-btn-container">
                @auth
                <a href="/publication">
                @else
                <a href="#" data-toggle="modal" data-target="#login">
                @endauth
                    <button class="btn-publier-header cusor p-2 mobile-publish-btn" type="button">
                        <i class="lni lni-circle-plus"></i>
                        <span>Publier</span>
                    </button>
                </a>
            </div>
        </div>
    </div>
</div>
<script>
            document.addEventListener('DOMContentLoaded', function() {
            const navToggle = document.querySelector('.nav-toggle');
            const sidebarWrapper = document.querySelector('.sidebar-wrapper');
            const closeMenu = document.querySelector('.close-menu');

            navToggle.addEventListener('click', function() {
            sidebarWrapper.classList.toggle('open'); // Toggle the sidebar on click
            });

            closeMenu.addEventListener('click', function() {
            sidebarWrapper.classList.remove('open'); // Close the sidebar when '×' is clicked
            });
            });
</script>
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
                    icons_position.find('.icon-icon-header').css('margin-left', '3px');
                } else {
                    icons_position.addClass("comment-position").removeClass("comment-position-top");
                    icons_position.find('.icon-icon-header').css('margin-left', '-10px');
                    comment_position.removeClass("comment-position").addClass("comment-position-top");
                    elementToHide.removeClass('d-none');
                }

            });
        </script>
        <!-- Start Navigation -->
        <div class="header header-light dark-text desktop-only">
            <div class="container">
                <nav id="navigation" class="navigation navigation-landscape">
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
                            </li>
                            <li>
                                <a href="{{ Auth::check() ? route('shopiners') : '#' }}"
                                    @guest data-toggle="modal" data-target="#login" @endguest>
                                    Shop<span class="color strong">in</span>ers
                                </a>

                            </li>
                            <li class="desktop-only">
                                <a href="{{ route('contact') }}">Contact</a>
                            </li>
                            <li class="elementToHideBeforeScroll d-none" style="margin-left: 50px; display: flex; align-items: center;">
                                <div class="div-sroll-recherche">
                                <form action="/shop" method="get" class="mobile-search-form">
                                    @csrf
                                    <div class="search-container">
                                        <input type="text" class="form-control sm input cusor border-r mobile-search-input" name="key" placeholder="rechercher un article">
                                        <button type="submit" class="search-button cusor mobile-search-btn">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </form>
                                </div>
                            </li>


                       <li class="elementToHideBeforeScroll hide-mobile-version d-none">
                            <div class="div-scroll-publier">
                                @auth
                                <a href="/publication" class="btn-publier-header cusor p-1">
                            @else
                                <a href="#" class="btn-publier-header cusor p-1" data-toggle="modal" data-target="#login">
                            @endauth
                                <span class="color small">
                                    <i class="lni lni-circle-plus"></i>
                                    Publier
                                </span>
                            </a>
                               </div>
                        </li>
                        <li class="option-icon-header comment-position-top" id="icons_position">
                            @auth
                            <a href="{{ route('historique',['type'=>'achats']) }}" class="ml-2 icon-icon-header" style="color: black !important; margin-left: 8px;">
                                <i class="bi lni bi-clock-history icon-icon-header"></i>
                            </a>
                            @endauth
                            <a href="#" onclick="openCart()" class="position-relative icon-icon-header" style="color: black !important;">
                                <i class="bi lni bi-bag icon-icon-header"></i>
                                <span class="dn-counter bg-success-ps" id="CountPanier-value">0</span>
                            </a>

                            @guest
                            <a href="#" data-toggle="modal" data-target="#login" class="icon-icon-header" style="color: black !important; margin-left: 8px;">
                                <i class="bi lni bi-person-circle icon-icon-header"></i>
                            </a>
                            @endguest

                            @auth
                            <a href="{{ route('user-notifications') }}" class="icon-icon-header" style="color: black !important; margin-left: 8px;">
                                <i class="lni bi bi-bell icon-icon-header"></i>
                                <span class="dn-counter bg-success-ps" id="CountNotification-value">0</span>
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

                                    <li class="text-capitalize comment-position">
                                        <a href="#" data-toggle="modal" data-target="#tarifaire">Nos Politiques Tarifaires</a>
                                        <ul class="nav-dropdown nav-submenu left-aligned">
                                            @foreach ($categories as $tarif)
                                            <li class="tarif-item">
                                                <span class="tarif-title">
                                                    {{ $tarif->titre }}
                                                    @if ($tarif->luxury == 1)
                                                    <span class="luxury-text">
                                                        <i class="bi bi-gem luxury-icon"></i> Luxury
                                                    </span>
                                                    @endif
                                                </span>
                                                <span class="tarif-percentage">{{ intval($tarif->pourcentage_gain) }}%</span>
                                            </li>
                                            @endforeach
                                            <!-- Separator and phrase -->
                                            <li class="tarif-separator"></li>
                                            <li class="tarif-note">
                                                Le montant de la commission est prélévé au moment du paiement par l'acheteur
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>

                        </ul>


                    </div>
                </nav>
            </div>
        </div>



        <script>
            // Close the navbar when any link is clicked unless it's within a modal
            document.querySelectorAll('.nav-menu a').forEach(item => {
                item.addEventListener('click', function (event) {
                    // Check if the click is outside the modal
                    if (!event.target.closest('.modal')) {
                        document.querySelector('.nav-menus-wrapper').classList.remove('nav-menus-wrapper-open');
                    }
                });
            });
        </script>
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
                                                Tarifaires</a></li>
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
                            Le montant de la comission est prélévé au moment du paiement par l'acheteur
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <div class="modal fade" id="conditions" tabindex="-1" role="dialog" aria-labelledby="conditionsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="conditionsLabel">Conditions générales</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"  id="conditiondiv">
                    @include('User.composants.text-conditions')

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark bg-dark" id="agreeConditionButton">
                        J'accepte les conditions
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->


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
                    <div id="content-liste">

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




        <!-- Modal pour modifier le prix -->
        <div class="modal fade" id="Modal-Update-Post-Price" tabindex="1" role="dialog"
            aria-labelledby="UpdatePrice" aria-hidden="true">
            <div class="modal-dialog modal-xl login-pop-form" role="document">
                <div class="modal-content" id="UpdatePrice">
                    <div class="modal-headers">
                        <button type="button" class="close" onclick="close_update_price()">
                            <span class="ti-close"></span>
                        </button>
                    </div>
                    <div class="modal-body p-5">
                        <div style="text-align: left;">
                            @livewire('User.UpdatePrix')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal pour modifier le prix -->
        <script>
            function close_update_price() {
                //reload page
                location.reload();
            }
        </script>

        </script>






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
    $(document).ready(function() {
    @if(session('clearLocalStorage'))
        localStorage.clear();
    @endif
    var storedUserId = localStorage.getItem('userId');
    var currentUserId = "{{ Auth::id() }}";
    if (storedUserId !== currentUserId) {
        localStorage.clear();
        localStorage.setItem('userId', currentUserId);
    }
    var conditionsAccepted = localStorage.getItem('conditionsAccepted');
    if (conditionsAccepted) {
        $("#validateCartButton").prop('disabled', false);
    }
    $("#agree_condition").click(function() {
        localStorage.setItem('conditionsAccepted', true);
        $('#conditions').modal('hide');
        $("#validateCartButton").prop('disabled', false);
        window.location.href = '/checkout?step=3'; // Redirect to the specified path
    });

    document.getElementById('conditiondiv').addEventListener('scroll', function() {
        document.getElementById('agreeConditionButton').disabled = false;
    });
});
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

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @yield('script')

</body>

</html>
