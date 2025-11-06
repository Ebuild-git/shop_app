<!DOCTYPE html>
<html lang="fr" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="/assets-admin/" data-template="horizontal-menu-template">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> @yield('titre') | {{ config('app.name', 'Shopin') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/icons/icone.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">


    <!-- Icons -->
    <link rel="stylesheet" href="/assets-admin/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="/assets-admin/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="/assets-admin/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="/assets-admin/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="/assets-admin/vendor/css/rtl/theme-default.css"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="/assets-admin/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="/assets-admin/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="/assets-admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="/assets-admin/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="/assets-admin/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet" href="/assets-admin/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="/assets-admin/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Page CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
        integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">

    <link rel="stylesheet" href="/assets-admin/vendor/css/pages/app-logistics-dashboard.css" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <!-- Helpers -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap -->

    <script src="/assets-admin/vendor/js/helpers.js"></script>
    <script src="/assets-admin/vendor/js/template-customizer.js"></script>
    <script src="/assets-admin/js/config.js"></script>
    <link rel="stylesheet" href="/admin-style.css">
    @yield('css')
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <script>
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
                    timer: 2500
                });
            });
        });
    </script>
    <style>
        .avatar-online {
            height: 35px !important;
            width: 35px !important;
            overflow: hidden;

        }

        .avatar-online img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }

        .table-red {
            background-color: #d32a2a;
        }

        .table-red tr th {
            color: white !important;
        }

    .table-responsive {
    max-height: 400px;
    overflow-y: auto;
    overflow-x: auto;
    }

    .table {
        min-width: 100%;
    }

    .table th,
    .table td {
        white-space: nowrap;
        text-align: left;
        padding: 8px 12px;
    }

    .table th {
        position: sticky;
        top: 0;
        z-index: 2;
        background-color: #343a40;
        color: #fff;
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
        font-size: 12px;
    }

    .table tbody td {
        border-bottom: 1px solid #dee2e6;
        font-size: 13px;
    }

    td:first-child,
    th:first-child {
        position: sticky;
        left: 0;
        z-index: 3;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); /* Optional shadow for better visibility */

    }
    td:nth-child(2), th:nth-child(2) {
    position: sticky;
    left: 75px; /* Adjust based on the width of the first column */
    z-index: 3; /* Ensure it's above other elements */
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); /* Optional shadow for better visibility */
    }
    td:nth-child(3), th:nth-child(3) {
    position: sticky;
    left: 120px; /* Adjust based on the combined width of the first two columns */
    z-index: 3;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); /* Optional shadow for better visibility */
}
    td:nth-child(2) {
        background-color: white; /* White background when scrolling */
    }
    td:first-child {
        background-color: white;
    }
    td:nth-child(3){
        background-color: white; /* White background when scrolling */
    }
    th:first-child, th:nth-child(2), th:nth-child(3){
        z-index: 4;
    }
    td:first-child, td:nth-child(2), td:nth-child(3) {
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }
    .table .action-buttons {
        display: flex;
        gap: 5px;
        align-items: center;
    }
    </style>


    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">
            <!-- Navbar -->

            <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
                <div class="container-xxl">
                    <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
                        <a href="/dashboard" class="app-brand-link gap-2">
                            <img src="/icons/logo.png" alt="" srcset="" style="height: 25px;">
                        </a>

                        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
                            <i class="ti ti-x ti-sm align-middle"></i>
                        </a>
                    </div>

                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="ti ti-menu-2 ti-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <ul class="navbar-nav flex-row align-items-center ms-auto">


                            <!-- Search -->
                            <li class="nav-item navbar-search-wrapper me-2 me-xl-0">
                                <a class="nav-link search-toggler" href="javascript:void(0);">
                                    <i class="ti ti-search ti-md"></i>
                                </a>
                            </li>
                            <!-- /Search -->

                            <!-- Style Switcher -->
                            <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <i class="ti ti-md"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                                            <span class="align-middle"><i class="ti ti-sun me-2"></i>Light</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                                            <span class="align-middle"><i class="ti ti-moon me-2"></i>Dark</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                                            <span class="align-middle"><i
                                                    class="ti ti-device-desktop me-2"></i>System</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- / Style Switcher-->

                            <!-- Notification -->
                            @livewire('Admin.NotificationsAdmin')
                            <!--/ Notification -->

                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{ Auth::user()->getAvatar() }}" alt
                                            class="h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="pages-account-settings-account.html">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="{{ Auth::user()->getAvatar() }}" alt
                                                            class="h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-medium d-block">
                                                        {{ Auth::user()->name }}
                                                    </span>
                                                    <small class="text-muted">
                                                        {{ Auth::user()->email }}
                                                    </small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin_settings') }}">
                                            <i class="ti ti-settings me-2 ti-sm"></i>
                                            <span class="align-middle">
                                                Parametres
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}">
                                            <i class="ti ti-logout me-2 ti-sm"></i>
                                            <span class="align-middle">Déconnexion</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>

                    <!-- Search Small Screens -->
                    <div class="navbar-search-wrapper search-input-wrapper container-xxl d-none">
                        <input type="text" class="form-control search-input border-0" placeholder="Search..."
                            aria-label="Search..." />
                        <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
                    </div>
                </div>
            </nav>

            <!-- / Navbar -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Menu -->
                    <aside id="layout-menu"
                        class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0">
                        <div class="container-xxl d-flex h-100">
                            <ul class="menu-inner">
                                <!-- Dashboards -->
                                <li class="menu-item">
                                    <a href="{{ route('dashboard') }}" class="menu-link ">
                                        <i class="menu-icon tf-icons bi bi-speedometer"></i>
                                        <div data-i18n="Dashboards">Dashboards</div>
                                    </a>
                                </li>

                                <!-- Layouts -->
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                                        <i class="menu-icon tf-icons bi bi-people-fill"></i>
                                        <div data-i18n="Utilisateurs">Utilisateurs</div>
                                    </a>

                                    <ul class="menu-sub">
                                        <li class="menu-item">
                                            <a href="{{ route('liste_utilisateurs') }}?type=all" class="menu-link">
                                                <i class="menu-icon tf-icons ti ti-menu-2"></i>
                                                <div data-i18n="Liste des utilisateurs">Liste des utilisateurs</div>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a href="{{ route('liste_utilisateurs_locked') }}?type=all&locked=yes" class="menu-link">
                                                <i class="menu-icon tf-icons ti ti-menu-2"></i>
                                                <div data-i18n="Liste des utilisateurs Bloqués">Liste des utilisateurs bloqués</div>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a href="{{ route('liste_utilisateurs_supprime') }}?type=all&showTrashed=yes" class="menu-link">
                                                <i class="menu-icon tf-icons ti ti-menu-2"></i>
                                                <div data-i18n="Liste des utilisateurs supprimés">Liste des utilisateurs supprimés</div>
                                            </a>
                                        </li>

                                    </ul>
                                </li>

                                <!-- publications -->
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                                        <i class="menu-icon tf-icons bi bi-grid-fill"></i>
                                        <div data-i18n="Publications">Publications</div>
                                    </a>
                                    <ul class="menu-sub">
                                        <li class="menu-item">
                                            <a href="/admin/publications" class="menu-link">
                                                <i class="menu-icon bi bi-stickies"></i>
                                                <div data-i18n="Liste des publications">Liste des publications</div>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a href="{{ route('liste_publications_supprimer') }}" class="menu-link">
                                                <i class="bi bi-trash3 menu-icon "></i>
                                                <div data-i18n="Liste des publications supprimés">Liste des
                                                    publications supprimés</div>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a href="{{ route('post_signalers') }}" class="menu-link">
                                                <i class="menu-icon bi bi-exclamation-triangle"></i>
                                                <div data-i18n="Publications signalés">Publications signalées</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <!-- Pages -->
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                                        <i class="menu-icon bi bi-gear"></i>

                                        <div data-i18n="Configuration">Configuration</div>
                                    </a>
                                    <ul class="menu-sub">
                                        <li class="menu-item">
                                            <a href="{{ route('gestion_categorie') }}" class="menu-link ">
                                                <i class="menu-icon tf-icons ti ti-files"></i>
                                                <div data-i18n="Catégories">Catégories</div>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a href="{{ route('gestion_proprietes') }}" class="menu-link ">
                                                <i class="menu-icon tf-icons ti ti-files"></i>
                                                <div data-i18n="Propriété des annonces">Propriété des annonces</div>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a href="{{ route('informations') }}" class="menu-link ">
                                                <i class="menu-icon tf-icons bi bi-sliders"></i>
                                                <div data-i18n="Informations visuelles">Informations visuelles</div>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a href="{{ route('nos_partenaires') }}" class="menu-link">
                                                <i class="menu-icon tf-icons ti ti-menu-2"></i>
                                                <div data-i18n="Nos partenaires">Nos partenaires</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                                        <i class="menu-icon bi bi-gear"></i>
                                        <div data-i18n="Commandes">Commandes</div>
                                    </a>
                                    <ul class="menu-sub">
                                        <li class="menu-item">
                                            <a href="{{ route('orders') }}" class="menu-link ">
                                                <i class="menu-icon tf-icons ti ti-files"></i>
                                                <div data-i18n="Liste des Commandes">Liste des Commandes</div>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a href="{{route('admin.orders.deleted')}}" class="menu-link ">
                                                <i class="menu-icon tf-icons ti ti-files"></i>
                                                <div data-i18n="Commandes supprimées">Commandes supprimées</div>
                                            </a>
                                        </li>

                                    </ul>
                                </li>

                            </ul>
                        </div>
                    </aside>
                    <!-- / Menu -->


                    @yield('body')


                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div
                                class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
                                <div>
                                    ©
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script>
                                    , made with ❤️ by
                                    <a href="https://e-build.tn" target="_blank" class="fw-medium"
                                        style="color: red;">
                                        <b>
                                            E-build
                                        </b>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!--/ Content wrapper -->
            </div>

            <!--/ Layout container -->
        </div>
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
    @yield('script')




    <!-- Modal send message 1-->
    <div class="modal fade" id="MessageModal" aria-labelledby="modalToggleLabel" tabindex="-1"
        style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalToggleLabel">
                        Envoyer un message à @<span id="destinataire">[distinataire]</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @livewire('Admin.SendMessage')
                </div>
            </div>
        </div>
    </div>


    <!-- Modal delete post -->
    <div class="modal fade" id="DeletePostModal" aria-labelledby="modalToggleLabel" tabindex="-1"
        style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content ">
                <div class="modal-header text-danger">
                    <h5 class="modal-title" id="modalToggleLabel">
                        Supprimer cette annonce
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @livewire('Admin.DeletePostModal')
                </div>
            </div>
        </div>
    </div>

    <script>
        function OpenModalMessage(user_id, username, titre, post_id, image) {
            Livewire.dispatch('sendDataUser', {
                username: username,
                user_id: user_id,
                titre: titre,
                post_id: post_id,
                image: image,
            });
            $("#destinataire").html(username);
            $('#MessageModal').modal('show');
        }

        function OpenModalDeletePost(id_post) {
            Livewire.dispatch('sendDataPostForDelete', {
                id_post: id_post
            });
            $('#DeletePostModal').modal('show');
        }


        document.addEventListener('livewire:init', () => {
            Livewire.on('annonce-delete', (event) => {
                $('#DeletePostModal').modal('hide');
            });
        });
    </script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

</body>

</html>
