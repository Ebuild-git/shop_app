<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets-user/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <title>SHOP - @yield('titre')</title>
    @yield('head')
</head>

<body class="font-weight-light">


    <!-- Just an image -->
    <nav class="navbar header-1">
        <a class="navbar-brand" href="/">
            <img src="data:image/png;base64,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"
                width="30" height="30" alt="">
        </a>
        @auth
            <div class="div-head-user dropdown">
                <div class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                    aria-haspopup="true">
                    <img src="{{ Storage::url(Auth::user()->avatar) }}" class="rounded-circle avatar-user-head">
                    <b>{{ Auth::user()->name }}</b>
                </div>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Déconnexion</a>
                </div>
            </div>
        @endauth
    </nav>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/">
            E-BUILD
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Accueil<span class="sr-only">(current)</span></a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="/mes-publication">
                            Mes publications
                        </a>
                    </li>
                @endauth
                <li class="nav-item">
                    <a class="nav-link" href="#">Contactez l'équipe</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Dropdown
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                @auth
                    <a href="/publication">
                        <button type="button" class="btn btn-outline-danger my-2 my-sm-0">
                            <i class="bi bi-pencil-square"></i>
                            Créer une publication
                        </button>
                    </a>
                @else
                    <a href="/inscription">
                        <button type="button" class="btn btn-outline-danger my-2 my-sm-0">
                            <i class="bi bi-person-circle"></i>
                            Créer un compte
                        </button>
                    </a>
                @endauth
                @guest
                    &nbsp;
                    <button class="btn btn-danger bg-red my-2 my-sm-0" type="button" data-toggle="modal"
                        data-target="#loginmodal">
                        Connexion
                    </button>
                @endguest
            </form>
        </div>
    </nav>

    @yield('body')
    @include('User.composants.login-modal')

    <section class="pb-3">
        <!-- Footer -->
        <footer class="text-center text-lg-start bg-body-tertiary text-muted bg-dark ">
            <!-- Section: Social media -->
            <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
                <!-- Left -->
                <div class="me-5 d-none d-lg-block">
                    <span>Get connected with us on social networks:</span>
                </div>
                <!-- Left -->

                <!-- Right -->
                <div>
                    <a href="" class="me-4 text-reset">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="" class="me-4 text-reset">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="" class="me-4 text-reset">
                        <i class="fab fa-google"></i>
                    </a>
                    <a href="" class="me-4 text-reset">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="" class="me-4 text-reset">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="" class="me-4 text-reset">
                        <i class="fab fa-github"></i>
                    </a>
                </div>
                <!-- Right -->
            </section>
            <!-- Section: Social media -->

            <!-- Section: Links  -->
            <section class="">
                <div class="container text-center text-md-start mt-5">
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
                © 2021 Copyright:
                <a class="text-reset fw-bold" href="https://mdbootstrap.com/">MDBootstrap.com</a>
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
