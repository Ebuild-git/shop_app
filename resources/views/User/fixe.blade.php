<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="shortcut icon" href="/icons/icone-orange.png" type="image/x-icon">
    <link rel="stylesheet" href="/assets-user/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <title>Shopin - @yield('titre')</title>
    @yield('head')
</head>

<body>
    @include('User.composants.sidebar')

    <!-- Just an image -->
    <nav class="navbar header-1">
        <a class="navbar-brand" href="/">
            <img src="/icons/logo-version-blanc.png" height="30" alt="">
        </a>
        
        @auth
            <div class="d-flex justify-content-end">
                <div class="mr-3">
                    <a href="{{ route('user-notifications') }}" class="notification-header-icon">
                        <i class="bi bi-bell"></i>
                    </a>
                </div>
                <div class=" dropdown">
                    <div class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                        aria-haspopup="true">
                        @livewire('User.MenuInformations')
                    </div>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item">
                            <div class="small">
                                <span class="color-orange">
                                    <b>Votre profil</b>
                                </span>
                            </div>
                        </a>
                        <a class="dropdown-item" href="/informations">
                            <i class="bi bi-sticky"></i>
                            Mes annonces
                        </a>
                        <a class="dropdown-item" href="/informations">
                            <i class="bi bi-bag"></i>
                            Mes achats
                        </a>
                        <a class="dropdown-item" href="/informations">
                            <i class="bi bi-bell"></i>
                            Notifications
                        </a>
                        <a class="dropdown-item" href="/informations">
                            <i class="bi bi-person"></i>
                            Mes informations
                        </a>
                        <a class="dropdown-item" href="/informations">
                            <i class="bi bi-shield-lock-fill"></i>
                            Sécurité
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/logout">
                            <i class="bi bi-box-arrow-right"></i> 
                            Déconnexion
                        </a>
                    </div>
                </div>
            </div>
        @endauth
    </nav>
    <nav class="navbar navbar-expand-lg nav-header-2">
        <a class="navbar-brand" href="#" onclick="openNav()">
            <i class="bi bi-list"></i> Toutes
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/">Accueil<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('shop') }}"><b>Market place</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/shop?etat=neuf">Produits neufs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/shop?etat=occasion">Produits d'occasions</a>
                </li>
                
            </ul>
            <form class="form-inline my-2 my-lg-0">
                @auth
                    <a href="/publication">
                        <button type="button" class="btn btn-light btn-sm my-2 my-sm-0">
                            <i class="bi bi-plus-circle-fill"></i>
                            Publier une annonce
                        </button>
                    </a>
                @else
                    <a href="/inscription">
                        <button type="button" class="btn btn-outline-red my-2 my-sm-0">
                            <i class="bi bi-person-circle"></i>
                            Créer un compte
                        </button>
                    </a>
                @endauth
                @guest
                    &nbsp;
                    <a href="/connexion">
                        <button class="btn btn-danger bg-red my-2 my-sm-0 no-border" type="button">
                            Connexion
                            <i class="bi bi-arrow-right-circle-fill"></i>
                        </button>
                    </a>
                @endguest
            </form>
        </div>
    </nav>


    @yield('body')


    <section class="">

        <!-- Footer -->

        <button class="btn-retour-haut" type="button">
            <i class="bi bi-arrow-up-circle"></i> Retour en haut
        </button>
        <section style="background-color: rgba(0, 0, 0, 0.05);" class="pt-2">
            <div class="container" >
                <div class="row">
                    <div class="col-sm-3 text-muted">
                        <a class="navbar-brand" href="/">
                            <img src="/icons/logo-version-noire.png" height="30" alt="">
                        </a>
                        <br>
                        <span class="h6">
                            Acheter et vendre gratuitement près de chez vous
                        </span>
                    </div>
                    <div class="col-sm-3">
                        <span class="h6">
                            Aide
                        </span>
                    </div>
                    <div class="col-sm-3">
                        <span class="h6">
                            Raccourcis
                        </span>
                    </div>
                    <div class="col-sm-3">
                        <span class="h6">
                            Contactez-nous
                        </span>
                        <img src="https://play.google.com/intl/en_us/badges/static/images/badges/en_badge_web_generic.png" class="w-100" alt="" srcset="">
                    </div>
                </div>
            </div>
        </section>
        <footer >
            <!-- Section: Links  -->
            <section class=" ">
                <div class="container text-center text-md-start">
                    <!-- Grid row -->
                    <div class="row mt-3">
                        <!-- Grid column -->
                        <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                            <!-- Content -->
                            <h6 class="text-uppercase fw-bold mb-4">
                                <i class="fas fa-gem me-3"></i>Company name
                            </h6>
                            <p>
                                Here you can use rows and columns to organize your footer content. Lorem ipsum
                                dolor sit amet, consectetur adipisicing elit.
                            </p>
                        </div>
                        <!-- Grid column -->

                        <!-- Grid column -->
                        <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                            <!-- Links -->
                            <h6 class="text-uppercase fw-bold mb-4">
                                Products
                            </h6>
                            <p>
                                <a href="#!" class="text-reset">Angular</a>
                            </p>
                            <p>
                                <a href="#!" class="text-reset">React</a>
                            </p>
                            <p>
                                <a href="#!" class="text-reset">Vue</a>
                            </p>
                            <p>
                                <a href="#!" class="text-reset">Laravel</a>
                            </p>
                        </div>
                        <!-- Grid column -->

                        <!-- Grid column -->
                        <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                            <!-- Links -->
                            <h6 class="text-uppercase fw-bold mb-4">
                                Useful links
                            </h6>
                            <p>
                                <a href="#!" class="text-reset">Pricing</a>
                            </p>
                            <p>
                                <a href="#!" class="text-reset">Settings</a>
                            </p>
                            <p>
                                <a href="#!" class="text-reset">Orders</a>
                            </p>
                            <p>
                                <a href="#!" class="text-reset">Help</a>
                            </p>
                        </div>
                        <!-- Grid column -->

                        <!-- Grid column -->
                        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                            <!-- Links -->
                            <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
                            <p><i class="fas fa-home me-3"></i> New York, NY 10012, US</p>
                            <p>
                                <i class="fas fa-envelope me-3"></i>
                                info@example.com
                            </p>
                            <p><i class="fas fa-phone me-3"></i> + 01 234 567 88</p>
                            <p><i class="fas fa-print me-3"></i> + 01 234 567 89</p>
                        </div>
                        <!-- Grid column -->
                    </div>
                    <!-- Grid row -->
                </div>
            </section>
            <!-- Section: Links  -->

            <!-- Copyright -->
            <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
                © 2024 Copyright:
                <a class="text-reset fw-bold" href="https://e-build.tn">B-build</a>
            </div>
            <!-- Copyright -->
        </footer>
        <!-- Footer -->
    </section>

    @yield('script')

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
</body>

</html>
