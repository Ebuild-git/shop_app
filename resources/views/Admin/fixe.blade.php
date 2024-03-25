<!DOCTYPE html>
<html lang="fr" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="/assets-admin/" data-template="horizontal-menu-template">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> @yield('titre') | {{ config('app.name', 'Shopin') }}</title>

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

    <link rel="stylesheet" href="/assets-admin/vendor/css/pages/app-logistics-dashboard.css" />

    <!-- Helpers -->
    <script src="/assets-admin/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="/assets-admin/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="/assets-admin/js/config.js"></script>
    @yield('css')
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
</head>

<body>

    @livewire('SweetAlert')
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">
            <!-- Navbar -->

            <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
                <div class="container-xxl">
                    <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
                        <a href="/dashboard" class="app-brand-link gap-2">
                            <img src="/icons/logo.png" alt="" srcset=""
                                style="height: 25px;">
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
                            <!-- Language -->
                            <li class="nav-item dropdown-language dropdown me-2 me-xl-0">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <i class="ti ti-language rounded-circle ti-md"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" data-language="en"
                                            data-text-direction="ltr">
                                            <span class="align-middle">English</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" data-language="fr"
                                            data-text-direction="ltr">
                                            <span class="align-middle">French</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" data-language="ar"
                                            data-text-direction="rtl">
                                            <span class="align-middle">Arabic</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" data-language="de"
                                            data-text-direction="ltr">
                                            <span class="align-middle">German</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--/ Language -->

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

                            <!-- Quick links  -->
                            <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <i class="ti ti-layout-grid-add ti-md"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end py-0">
                                    <div class="dropdown-menu-header border-bottom">
                                        <div class="dropdown-header d-flex align-items-center py-3">
                                            <h5 class="text-body mb-0 me-auto">Shortcuts</h5>
                                            <a href="javascript:void(0)" class="dropdown-shortcuts-add text-body"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Add shortcuts"><i class="ti ti-sm ti-apps"></i></a>
                                        </div>
                                    </div>
                                    <div class="dropdown-shortcuts-list scrollable-container">
                                        <div class="row row-bordered overflow-visible g-0">
                                            <div class="dropdown-shortcuts-item col">
                                                <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                                    <i class="ti ti-calendar fs-4"></i>
                                                </span>
                                                <a href="app-calendar.html" class="stretched-link">Calendar</a>
                                                <small class="text-muted mb-0">Appointments</small>
                                            </div>
                                            <div class="dropdown-shortcuts-item col">
                                                <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                                    <i class="ti ti-file-invoice fs-4"></i>
                                                </span>
                                                <a href="app-invoice-list.html" class="stretched-link">Invoice App</a>
                                                <small class="text-muted mb-0">Manage Accounts</small>
                                            </div>
                                        </div>
                                        <div class="row row-bordered overflow-visible g-0">
                                            <div class="dropdown-shortcuts-item col">
                                                <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                                    <i class="ti ti-users fs-4"></i>
                                                </span>
                                                <a href="app-user-list.html" class="stretched-link">User App</a>
                                                <small class="text-muted mb-0">Manage Users</small>
                                            </div>
                                            <div class="dropdown-shortcuts-item col">
                                                <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                                    <i class="ti ti-lock fs-4"></i>
                                                </span>
                                                <a href="app-access-roles.html" class="stretched-link">Role
                                                    Management</a>
                                                <small class="text-muted mb-0">Permission</small>
                                            </div>
                                        </div>
                                        <div class="row row-bordered overflow-visible g-0">
                                            <div class="dropdown-shortcuts-item col">
                                                <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                                    <i class="ti ti-chart-bar fs-4"></i>
                                                </span>
                                                <a href="index.html" class="stretched-link">Dashboard</a>
                                                <small class="text-muted mb-0">User Profile</small>
                                            </div>
                                            <div class="dropdown-shortcuts-item col">
                                                <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                                    <i class="ti ti-settings fs-4"></i>
                                                </span>
                                                <a href="pages-account-settings-account.html"
                                                    class="stretched-link">Setting</a>
                                                <small class="text-muted mb-0">Account Settings</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!-- Quick links -->

                            <!-- Notification -->
                            @livewire('Admin.NotificationsAdmin')
                            <!--/ Notification -->

                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{ Storage::url(Auth::user()->avatar)}}" alt
                                            class="h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="pages-account-settings-account.html">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="{{ Storage::url(Auth::user()->avatar)}}" alt
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
                                            <a href="{{ route('liste_utilisateurs') }}?type=shop" class="menu-link">
                                                <i class="menu-icon tf-icons ti ti-layout-distribute-vertical"></i>
                                                <div data-i18n="Liste des boutiques">Liste des boutiques</div>
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
                                            <a href="/admin/publications?type=signalés" class="menu-link">
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
</body>

</html>
