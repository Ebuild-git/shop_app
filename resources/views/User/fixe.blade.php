@php
    $configurations = DB::table('configurations')->first();
@endphp
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="author" content="Themezhub" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('titre') | {{ config('app.name', 'Shopin') }}</title>
    <link rel="stylesheet" href="/style.css">
    <!-- Custom CSS -->
    <link href="/assets/css/styles.css" rel="stylesheet">
    <link rel="shortcut icon" href="/icons/icone.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
        integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    @livewireStyles
    @yield('head')
</head>

<body>

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

                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-5 col-sm-12 float-right d-flex justify-content-end">
                        <div class="top_first hide-ipad">
                            Appelez le:
                            <a href="callto:{{ $configurations->phone_number ?? '' }}" class="medium text-light">
                                {{ $configurations->phone_number ?? '' }}
                            </a>
                        </div>
                        <div class="currency-selector dropdown js-dropdown ml-3">
                            @auth
                                @livewire('User.MenuInformations')
                            @endauth
                            <a href="javascript:void(0);" class="text-light medium text-capitalize"
                                data-toggle="dropdown" title="Language" aria-label="Language dropdown">
                                @auth
                                    {{ Auth::user()->name }}
                                    <i class="fa fa-angle-down medium text-light"></i>
                                    <ul class="dropdown-menu popup-content link">
                                        <li>
                                            <a href="/user-notifications" class="dropdown-item medium text-medium">
                                                Notifications
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/mes-publication" class="dropdown-item medium text-medium">
                                                Mes annonces
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/mes-achats" class="dropdown-item medium text-medium">
                                                Mes achats
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/informations" class="dropdown-item medium text-medium">
                                                Sécurité
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/logout" class="dropdown-item medium text-medium text-danger">
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
                        <div class="col-10 recherche-div">
                            <form action="/shop" method="get">
                                @csrf
                                <input type="text" class="form-control sm text-capitalize input" name="key"
                                    placeholder="recherche un produit">
                                <button type="submit" class="span-icon-recherche">
                                    <i class="bi bi-search"></i>
                                </button>
                            </form>
                        </div>

                        <div class="col-2" style="text-align: left !important;">
                            @auth
                                <a href="/publication">
                                @else
                                    <a href="#" data-toggle="modal" data-target="#login">
                                    @endauth
                                    <button class="btn btn-sm  bg-dark text-light p-2" type="button">
                                        <i class="lni lni-circle-plus"></i>
                                        <span class="hide-mobile-version">
                                            Publier
                                        </span>
                                    </button>
                                </a>
                        </div>

                    </div>
                </div>
                <div class="col-sm-2 mx-auto my-auto">

                </div>
            </div>
        </div>

        <script>
            window.addEventListener('scroll', function() {
                var elementToHide = document.getElementById('elementToHideBeforeScroll');
                var icons_position = document.getElementById('icons_position');
                var comment_position = document.getElementById('comment_position');
                var scrollPosition = window.scrollY;

                if (scrollPosition === 0) {
                    elementToHide.classList.add('d-none');
                    comment_position.classList.add("comment-position");
                    comment_position.classList.remove("comment-position-top");

                    icons_position.classList.remove("comment-position");
                    icons_position.classList.add("comment-position-top");

                } else {


                    icons_position.classList.add("comment-position");
                    icons_position.classList.remove("comment-position-top");

                    comment_position.classList.remove("comment-position");
                    comment_position.classList.add("comment-position-top");
                    elementToHide.classList.remove('d-none');
                }
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

                            <li id="elementToHideBeforeScroll" class="d-none">
                                <a href="/">
                                    <img src="/icons/logo.png" class="logo" alt="" height="20" />
                                </a>
                            </li>
                            <li>
                                <a href="/">Accueil</a>
                            </li>


                            <li>
                                <a href="/about">À PROPOS</a>
                            </li>

                            <li>
                                @php
                                    $categories = DB::table('categories')->get(['id', 'titre']);
                                @endphp
                                <a href="/shop">CATÉGORIES</a>
                                <ul class="nav-dropdown nav-submenu">
                                    @forelse ($categories as $item)
                                        <li>
                                            <a href="/shop?categorie={{ $item->id }}">
                                                {{ $item->titre }}
                                            </a>
                                        </li>
                                    @empty
                                    @endforelse
                                </ul>
                            </li>
                            <li>
                                <a href="#">Shop<span class="color">in</span>ers</a>
                            </li>
                            <li>
                                <a href="#">Contact</a>
                            </li>
                            <li class="option-icon-header comment-position-top" id="icons_position">
                                @auth
                                    <a href="{{ route('historique') }}">
                                        <i class="bi bi-clock-history"></i>
                                        <span class="hide-desktop">Historique</span>
                                    </a>
                                @endauth

                                @livewire('User.CountPanier')

                                @guest
                                    <a href="#" data-toggle="modal" data-target="#login">
                                        <i class="bi lni  bi-person-circle"></i>
                                        <span class="hide-desktop">Connexion</span>
                                    </a>
                                @endguest
                                @auth
                                    @livewire('User.NotificationsUser')
                                @endauth
                            </li>
                            <li class="text-capitalize comment-position" id="comment_position">
                                <a href="#">Comment ça marche?</a>
                                <ul class="nav-dropdown nav-submenu">
                                    <li>
                                        <a href="/faqs">
                                            Comment Vendre ou acheter?
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/conditions">
                                            Conditions générales
                                        </a>
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


        @yield('script')
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

                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12">
                                <div class="footer_widget">
                                    <h4 class="widget_title">Supports</h4>
                                    <ul class="footer-menu">
                                        <li><a href="#">Contact Us</a></li>
                                        <li><a href="#">About Page</a></li>
                                        <li><a href="#">Size Guide</a></li>
                                        <li><a href="#">Shipping & Returns</a></li>
                                        <li><a href="#">FAQ's Page</a></li>
                                        <li><a href="#">Privacy</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12">
                                <div class="footer_widget">
                                    <h4 class="widget_title">Shop</h4>
                                    <ul class="footer-menu">
                                        <li><a href="#">Men's Shopping</a></li>
                                        <li><a href="#">Women's Shopping</a></li>
                                        <li><a href="#">Kids's Shopping</a></li>
                                        <li><a href="#">Furniture</a></li>
                                        <li><a href="#">Discounts</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12">
                                <div class="footer_widget">
                                    <h4 class="widget_title">Company</h4>
                                    <ul class="footer-menu">
                                        <li><a href="#">About</a></li>
                                        <li><a href="#">Blog</a></li>
                                        <li><a href="#">Affiliate</a></li>
                                        <li><a href="#">Login</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                <div class="footer_widget">
                                    <h4 class="widget_title">Subscribe</h4>
                                    <div class="address mt-3">
                                        <h5 class="fs-sm">Secure Payments</h5>
                                        <div class="scr_payment"><img src="/assets/img/card.png" class="img-fluid"
                                                alt="" /></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="footer-bottom" style="background-color: #008080 !important;color: black;">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-12 col-md-12 text-center">
                                <p class="mb-0">© {{ date('Y') }}.
                                    Designd By <a href="https://e-build.tn" style="color: #c71f17;">
                                        <b>E-build</b>
                                    </a>.
                                </p>
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


    <!-- Product View Modal -->
    <div class="modal fade lg-modal" id="quickview" tabindex="-1" role="dialog" aria-labelledby="quickviewmodal"
        aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="quickviewmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="quick_view_wrap">

                        <div class="quick_view_thmb">
                            <div class="quick_view_slide">
                                <div class="single_view_slide"><img src="https://via.placeholder.com/625x800"
                                        class="img-fluid" alt="" /></div>
                                <div class="single_view_slide"><img src="https://via.placeholder.com/625x800"
                                        class="img-fluid" alt="" /></div>
                                <div class="single_view_slide"><img src="https://via.placeholder.com/625x800"
                                        class="img-fluid" alt="" /></div>
                                <div class="single_view_slide"><img src="https://via.placeholder.com/625x800"
                                        class="img-fluid" alt="" /></div>
                            </div>
                        </div>

                        <div class="quick_view_capt">
                            <div class="prd_details">

                                <div class="prt_01 mb-1"><span
                                        class="text-light bg-info rounded px-2 py-1">Dresses</span></div>
                                <div class="prt_02 mb-2">
                                    <h2 class="ft-bold mb-1">Women Striped Shirt Dress</h2>
                                    <div class="text-left">
                                        <div
                                            class="star-rating align-items-center d-flex justify-content-left mb-1 p-0">
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star"></i>
                                            <span class="small">(412 Reviews)</span>
                                        </div>
                                        <div class="elis_rty"><span
                                                class="ft-medium text-muted line-through fs-md mr-2">$199</span><span
                                                class="ft-bold theme-cl fs-lg mr-2">$110</span><span
                                                class="ft-regular text-danger bg-light-danger py-1 px-2 fs-sm">Out
                                                of Stock</span></div>
                                    </div>
                                </div>

                                <div class="prt_03 mb-3">
                                    <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis
                                        praesentium voluptatum deleniti atque corrupti quos dolores.</p>
                                </div>

                                <div class="prt_04 mb-2">
                                    <p class="d-flex align-items-center mb-0 text-dark ft-medium">Color:</p>
                                    <div class="text-left">
                                        <div class="form-check form-option form-check-inline mb-1">
                                            <input class="form-check-input" type="radio" name="color8"
                                                id="white8">
                                            <label class="form-option-label rounded-circle" for="white8"><span
                                                    class="form-option-color rounded-circle blc7"></span></label>
                                        </div>
                                        <div class="form-check form-option form-check-inline mb-1">
                                            <input class="form-check-input" type="radio" name="color8"
                                                id="blue8">
                                            <label class="form-option-label rounded-circle" for="blue8"><span
                                                    class="form-option-color rounded-circle blc2"></span></label>
                                        </div>
                                        <div class="form-check form-option form-check-inline mb-1">
                                            <input class="form-check-input" type="radio" name="color8"
                                                id="yellow8">
                                            <label class="form-option-label rounded-circle" for="yellow8"><span
                                                    class="form-option-color rounded-circle blc5"></span></label>
                                        </div>
                                        <div class="form-check form-option form-check-inline mb-1">
                                            <input class="form-check-input" type="radio" name="color8"
                                                id="pink8">
                                            <label class="form-option-label rounded-circle" for="pink8"><span
                                                    class="form-option-color rounded-circle blc3"></span></label>
                                        </div>
                                        <div class="form-check form-option form-check-inline mb-1">
                                            <input class="form-check-input" type="radio" name="color8"
                                                id="red">
                                            <label class="form-option-label rounded-circle" for="red"><span
                                                    class="form-option-color rounded-circle blc4"></span></label>
                                        </div>
                                        <div class="form-check form-option form-check-inline mb-1">
                                            <input class="form-check-input" type="radio" name="color8"
                                                id="green">
                                            <label class="form-option-label rounded-circle" for="green"><span
                                                    class="form-option-color rounded-circle blc6"></span></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="prt_04 mb-4">
                                    <p class="d-flex align-items-center mb-0 text-dark ft-medium">Size:</p>
                                    <div class="text-left pb-0 pt-2">
                                        <div class="form-check size-option form-option form-check-inline mb-2">
                                            <input class="form-check-input" type="radio" name="size"
                                                id="28" checked="">
                                            <label class="form-option-label" for="28">28</label>
                                        </div>
                                        <div class="form-check form-option size-option  form-check-inline mb-2">
                                            <input class="form-check-input" type="radio" name="size"
                                                id="30">
                                            <label class="form-option-label" for="30">30</label>
                                        </div>
                                        <div class="form-check form-option size-option  form-check-inline mb-2">
                                            <input class="form-check-input" type="radio" name="size"
                                                id="32">
                                            <label class="form-option-label" for="32">32</label>
                                        </div>
                                        <div class="form-check form-option size-option  form-check-inline mb-2">
                                            <input class="form-check-input" type="radio" name="size"
                                                id="34">
                                            <label class="form-option-label" for="34">34</label>
                                        </div>
                                        <div class="form-check form-option size-option  form-check-inline mb-2">
                                            <input class="form-check-input" type="radio" name="size"
                                                id="36">
                                            <label class="form-option-label" for="36">36</label>
                                        </div>
                                        <div class="form-check form-option size-option  form-check-inline mb-2">
                                            <input class="form-check-input" type="radio" name="size"
                                                id="38">
                                            <label class="form-option-label" for="38">38</label>
                                        </div>
                                        <div class="form-check form-option size-option  form-check-inline mb-2">
                                            <input class="form-check-input" type="radio" name="size"
                                                id="40">
                                            <label class="form-option-label" for="40">40</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="prt_05 mb-4">
                                    <div class="form-row mb-7">
                                        <div class="col-12 col-lg-auto">
                                            <!-- Quantity -->
                                            <select class="mb-2 custom-select">
                                                <option value="1" selected="">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg">
                                            <!-- Submit -->
                                            <button type="submit" class="btn btn-block custom-height bg-dark mb-2">
                                                <i class="lni lni-shopping-basket mr-2"></i>Add to Cart
                                            </button>
                                        </div>
                                        <div class="col-12 col-lg-auto">
                                            <!-- Wishlist -->
                                            <button class="btn custom-height btn-default btn-block mb-2 text-dark"
                                                data-toggle="button">
                                                <i class="lni lni-heart mr-2"></i>Wishlist
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="prt_06">
                                    <p class="mb-0 d-flex align-items-center">
                                        <span class="mr-4">Share:</span>
                                        <a class="d-inline-flex align-items-center justify-content-center p-3 gray circle fs-sm text-muted mr-2"
                                            href="#!">
                                            <i class="fab fa-twitter position-absolute"></i>
                                        </a>
                                        <a class="d-inline-flex align-items-center justify-content-center p-3 gray circle fs-sm text-muted mr-2"
                                            href="#!">
                                            <i class="fab fa-facebook-f position-absolute"></i>
                                        </a>
                                        <a class="d-inline-flex align-items-center justify-content-center p-3 gray circle fs-sm text-muted"
                                            href="#!">
                                            <i class="fab fa-pinterest-p position-absolute"></i>
                                        </a>
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->



    <!-- Cart -->
    <div class="w3-ch-sideBar w3-bar-block w3-card-2 w3-animate-right" style="display:none;right:0;" id="Cart">
        <div class="rightMenu-scroll">
            <div class="d-flex align-items-center justify-content-between slide-head py-3 px-3">
                <h4 class="cart_heading fs-md ft-medium mb-0">
                    Contenu du panier
                </h4>
                <button onclick="closeCart()" class="close_slide"><i class="ti-close"></i></button>
            </div>
            <div class="right-ch-sideBar">
                <div class="cart_select_items py-2">
                    @livewire('User.Panier')
                    <div class="cart_action px-3 py-3">
                        <div class="form-group">
                           <a href="/checkout">
                            <button type="button" class="btn d-block full-width btn-dark">
                                Finaliser les achats
                            </button>
                        </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>



    <a id="back2Top" class="top-scroll" title="Back to top" href="#"><i class="ti-arrow-up"></i></a>




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
                        <button type="button" class="btn  bg-dark  btn-sm" disabled id="agree_condition">
                            J'accepte les conditions
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
    <script>
        $(document).ready(function() {
            // Vérifier si l'utilisateur a déjà accepté les conditions
            var conditionsAccepted = localStorage.getItem('conditionsAccepted');
            // Si les conditions n'ont pas été acceptées
            if (!conditionsAccepted) {
                // Afficher la modal des conditions
                $('#conditions').modal('show');
                $("#agree_condition").click(function() {
                    localStorage.setItem('conditionsAccepted', true);
                    $('#conditions').modal('hide');
                });
            }
        });

        document.getElementById('conditiondiv').addEventListener('scroll', function() {
            var div = this;
            // Vérifier si l'utilisateur a atteint la fin de la div
            if (div.scrollHeight - div.scrollTop === div.clientHeight) {
                // Activer le bouton
                document.getElementById('agree_condition').disabled = false;
            } else {
                // Désactiver le bouton
                document.getElementById('agree_condition').disabled = true;
            }
        });
    </script>
    <style>
        .modal-dialog-scrollable {
            overflow-y: auto;
            overflow-x: hidden;
            height: 500px;
        }
    </style>
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
        function openSearch() {
            document.getElementById("Search").style.display = "block";
        }

        function closeSearch() {
            document.getElementById("Search").style.display = "none";
        }
    </script>

</body>

</html>
