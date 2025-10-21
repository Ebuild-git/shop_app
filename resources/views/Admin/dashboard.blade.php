@extends('Admin.fixe')
@section('titre', 'Dashboard')
@section('content')


@section('body')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="py-3 mb-4"><span class="text-muted fw-light">
            {{ config('app.name', 'Shopin') }} /</span> Dashboard
        </h5>

        <!-- Card Border Shadow -->
        <div class="row">
            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="fa-regular fa-user"></i>
                                </span>

                            </div>
                            <h4 class="ms-1 mb-0">
                                {{ DB::table("users")->where('locked', false)->where("role","user")->whereNull('deleted_at')->count() }}
                            </h4>
                        </div>
                        <p class="mb-1">
                            <a href="/admin/utilisateurs">
                                Vendeurs particuliers
                            </a>
                        </p>
                        <p class="mb-0">
                            <small class="text-muted">
                                Nombre total des vendeurs particuliers
                            </small>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="fa-solid fa-folder"></i>
                                </span>
                            </div>
                            <h4 class="ms-1 mb-0">
                                {{ DB::table("categories")->count() }}
                            </h4>
                        </div>
                        <p class="mb-1">
                            <a href="/admin/categorie">
                                Catégories
                            </a>
                        </p>
                        <p class="mb-0">
                            <small class="text-muted">
                                Nombre total des Catégories
                            </small>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-danger">
                                    <i class="fa-solid fa-file"></i>
                                </span>
                            </div>
                            <h4 class="ms-1 mb-0">
                                {{ DB::table("posts")->count() }}
                            </h4>
                        </div>
                        <p class="mb-1">
                            <a href="/admin/publications">
                                Publications
                            </a>
                        </p>
                        <p class="mb-0">
                            <small class="text-muted">
                                Publications totales de la plate-forme
                            </small>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="fa-solid fa-money-bill-trend-up"></i>
                                </span>
                            </div>
                            <h4 class="ms-1 mb-0">
                                {{ DB::table("posts")->where("sell_at","!=",null)->count() }}
                            </h4>
                        </div>
                        <p class="mb-1">Ventes</p>
                        <p class="mb-0">
                            <small class="text-muted">
                                Nombre total des ventes
                            </small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Card Border Shadow -->
        <div class="row">

            <!-- Reasons for delivery exceptions -->
            <div class="col-xxl-6 mb-4 order-5 order-xxl-0">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">
                                Statistiques d'inscription par genre.
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="deliveryExceptionsChart" class="pt-md-4" data-genres="{{ json_encode($genres) }}"></div>
                    </div>
                </div>
            </div>
            <!--/ Reasons for delivery exceptions -->
            <!-- Shipment statistics-->
            <div class="col-lg-6 col-xxl-6 mb-4 order-5 order-xxl-0">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Statistiques du trafic</h5>
                            <small class="text-muted">Inscriptions et publications par mois</small>
                        </div>
                        <div class="dropdown">
                            <button type="button" class="btn btn-label-primary dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ $year }}
                            </button>
                            <ul class="dropdown-menu">
                                @for ($i = date("Y"); $i < date("Y") + 10; $i++)
                                    <li>
                                        <a class="dropdown-item" href="{{ url('/dashboard?das_date=' . $i) }}">
                                            {{ $i }}
                                        </a>
                                    </li>
                                @endfor
                            </ul>
                        </div>
                    </div>

                    <div class="card-body">
                        <div id="shipmentStatisticsChart"
                            data-inscription='@json($stats_inscription_publication["inscription"])'
                            data-publication='@json($stats_inscription_publication["publication"])'>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--/ Content -->
@endsection



@section('script')
    <script src="./assets-admin/vendor/libs/jquery/jquery.js"></script>
    <script src="./assets-admin/vendor/libs/popper/popper.js"></script>
    <script src="./assets-admin/vendor/js/bootstrap.js"></script>
    <script src="./assets-admin/vendor/libs/node-waves/node-waves.js"></script>
    <script src="./assets-admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="./assets-admin/vendor/libs/hammer/hammer.js"></script>
    <script src="./assets-admin/vendor/libs/i18n/i18n.js"></script>
    <script src="./assets-admin/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="./assets-admin/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="./assets-admin/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="./assets-admin/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>

    <!-- Main JS -->
    <script src="./assets-admin/js/main.js"></script>

    <!-- Page JS -->
    <script src="./assets-admin/js/app-logistics-dashboard.js"></script>
@endsection
@endsection
