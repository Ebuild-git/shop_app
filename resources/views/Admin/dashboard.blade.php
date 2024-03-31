@extends('Admin.fixe')
@section('title', 'Accueil')
@section('content')


@section('body')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">
            {{ config('app.name', 'Shopin') }} /</span> Dashboard
        </h4>

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
                                {{ DB::table("users")->where("type","user")->count() }}
                            </h4>
                        </div>
                        <p class="mb-1">
                            <a href="/admin/utilisateurs">
                                Vendeurs particuliers
                            </a>
                        </p>
                        <p class="mb-0">
                            <small class="text-muted">
                                Nombre total des vendeurs particuliers !
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
                                Nombre total des Catégories !
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
                                Publications totales de la plate-forme !
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
                                Nombre total des ventes !
                            </small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Card Border Shadow -->
        <div class="row">
            <!-- Vehicles overview -->
            <div class="col-xxl-6 mb-4 order-5 order-xxl-0">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title mb-0">
                            <h5 class="m-0">Vehicles overview</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-none d-lg-flex vehicles-progress-labels mb-4">
                            <div class="vehicles-progress-label on-the-way-text" style="width: 39.7%">On the way</div>
                            <div class="vehicles-progress-label unloading-text" style="width: 28.3%">Unloading</div>
                            <div class="vehicles-progress-label loading-text" style="width: 17.4%">
                                Loading</div>
                            <div class="vehicles-progress-label waiting-text text-nowrap" style="width: 14.6%">Waiting</div>
                        </div>
                        <div class="vehicles-overview-progress progress rounded-2 my-4" style="height: 46px">
                            <div class="progress-bar fw-medium text-start bg-body text-dark px-3 rounded-0"
                                role="progressbar" style="width: 39.7%" aria-valuenow="39.7" aria-valuemin="0"
                                aria-valuemax="100">
                                39.7%
                            </div>
                            <div class="progress-bar fw-medium text-start bg-primary px-3" role="progressbar"
                                style="width: 28.3%" aria-valuenow="28.3" aria-valuemin="0" aria-valuemax="100">
                                28.3%
                            </div>
                            <div class="progress-bar fw-medium text-start text-bg-info px-3" role="progressbar"
                                style="width: 17.4%" aria-valuenow="17.4" aria-valuemin="0" aria-valuemax="100">
                                17.4%
                            </div>
                            <div class="progress-bar fw-medium text-start bg-gray-900 px-2 rounded-0 px-lg-2 px-xxl-3"
                                role="progressbar" style="width: 14.6%" aria-valuenow="14.6" aria-valuemin="0"
                                aria-valuemax="100">
                                14.6%
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table">
                                <tbody class="table-border-bottom-0">
                                    <tr>
                                        <td class="w-50 ps-0">
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="me-2">
                                                    <i class="ti ti-truck mt-n1"></i>
                                                </div>
                                                <h6 class="mb-0 fw-normal">On the way</h6>
                                            </div>
                                        </td>
                                        <td class="text-end pe-0 text-nowrap">
                                            <h6 class="mb-0">2hr 10min</h6>
                                        </td>
                                        <td class="text-end pe-0">
                                            <span class="fw-medium">39.7%</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 ps-0">
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="me-2">
                                                    <i class="ti ti-circle-arrow-down mt-n1"></i>
                                                </div>
                                                <h6 class="mb-0 fw-normal">Unloading</h6>
                                            </div>
                                        </td>
                                        <td class="text-end pe-0 text-nowrap">
                                            <h6 class="mb-0">3hr 15min</h6>
                                        </td>
                                        <td class="text-end pe-0">
                                            <span class="fw-medium">28.3%</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 ps-0">
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="me-2">
                                                    <i class="ti ti-circle-arrow-up mt-n1"></i>
                                                </div>
                                                <h6 class="mb-0 fw-normal">Loading</h6>
                                            </div>
                                        </td>
                                        <td class="text-end pe-0 text-nowrap">
                                            <h6 class="mb-0">1hr 24min</h6>
                                        </td>
                                        <td class="text-end pe-0">
                                            <span class="fw-medium">17.4%</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 ps-0">
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="me-2">
                                                    <i class="ti ti-clock mt-n1"></i>
                                                </div>
                                                <h6 class="mb-0 fw-normal">Waiting</h6>
                                            </div>
                                        </td>
                                        <td class="text-end pe-0 text-nowrap">
                                            <h6 class="mb-0">5hr 19min</h6>
                                        </td>
                                        <td class="text-end pe-0">
                                            <span class="fw-medium">14.6%</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Vehicles overview -->
            <!-- Shipment statistics-->
            <div class="col-lg-6 col-xxl-6 mb-4 order-3 order-xxl-1">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Statistiques du trafic</h5>
                            <small class="text-muted">
                                
                            </small>
                        </div>
                        <div class="dropdown">
                            <button type="button" class="btn btn-label-primary dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ $date }}
                            </button>
                            <ul class="dropdown-menu">
                                @for ($i =  date("Y") ; $i <  date("Y")+10 ; $i++)
                                <li>
                                    <a class="dropdown-item" href="/dashboard?das_date={{ $i }}">
                                        {{ $i }}
                                    </a>
                                </li>
                                @endfor
                               
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="shipmentStatisticsChart" data-inscription='@json($stats_inscription_publication['inscription'])' data-publication='@json($stats_inscription_publication['publication'])'>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Shipment statistics -->
            <!-- Delivery Performance -->
            <div class="col-lg-6 col-xxl-4 mb-4 order-2 order-xxl-2">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between mb-2">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Delivery Performance</h5>
                            <small class="text-muted">12% increase in this month</small>
                        </div>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="deliveryPerformance" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="deliveryPerformance">
                                <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                <a class="dropdown-item" href="javascript:void(0);">Share</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="p-0 m-0">
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i
                                            class="ti ti-package"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-normal">Packages in transit</h6>
                                        <small class="text-success fw-normal d-block">
                                            <i class="ti ti-chevron-up mb-1"></i>
                                            25.8%
                                        </small>
                                    </div>
                                    <div class="user-progress">
                                        <h6 class="mb-0">10k</h6>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-info"><i class="ti ti-truck"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-normal">Packages out for delivery</h6>
                                        <small class="text-success fw-normal d-block">
                                            <i class="ti ti-chevron-up mb-1"></i>
                                            4.3%
                                        </small>
                                    </div>
                                    <div class="user-progress">
                                        <h6 class="mb-0">5k</h6>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-success"><i
                                            class="ti ti-circle-check"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-normal">Packages delivered</h6>
                                        <small class="text-danger fw-normal d-block">
                                            <i class="ti ti-chevron-down mb-1"></i>
                                            12.5%
                                        </small>
                                    </div>
                                    <div class="user-progress">
                                        <h6 class="mb-0">15k</h6>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-warning"><i
                                            class="ti ti-percentage"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-normal">Delivery success rate</h6>
                                        <small class="text-success fw-normal d-block">
                                            <i class="ti ti-chevron-up mb-1"></i>
                                            35.6%
                                        </small>
                                    </div>
                                    <div class="user-progress">
                                        <h6 class="mb-0">95%</h6>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-secondary"><i
                                            class="ti ti-clock"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-normal">Average delivery time</h6>
                                        <small class="text-danger fw-normal d-block">
                                            <i class="ti ti-chevron-down mb-1"></i>
                                            2.15%
                                        </small>
                                    </div>
                                    <div class="user-progress">
                                        <h6 class="mb-0">2.5 Days</h6>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-danger"><i
                                            class="ti ti-users"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-normal">Customer satisfaction</h6>
                                        <small class="text-success fw-normal d-block">
                                            <i class="ti ti-chevron-up mb-1"></i>
                                            5.7%
                                        </small>
                                    </div>
                                    <div class="user-progress">
                                        <h6 class="mb-0">4.5/5</h6>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--/ Delivery Performance -->
            <!-- Reasons for delivery exceptions -->
            <div class="col-md-6 col-xxl-4 mb-4 order-1 order-xxl-3">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Reasons for delivery exceptions</h5>
                        </div>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="deliveryExceptions" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="deliveryExceptions">
                                <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                <a class="dropdown-item" href="javascript:void(0);">Share</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="deliveryExceptionsChart" class="pt-md-4"></div>
                    </div>
                </div>
            </div>
            <!--/ Reasons for delivery exceptions -->
            <!-- Orders by Countries -->
            <div class="col-md-6 col-xxl-4 mb-4 order-0 order-xxl-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between pb-2">
                        <div class="card-title mb-1">
                            <h5 class="m-0 me-2">Orders</h5>
                            <small class="text-muted">62 Deliveries in Progress</small>
                        </div>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="salesByCountryTabs" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="salesByCountryTabs">
                                <a class="dropdown-item" href="javascript:void(0);">Download</a>
                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                <a class="dropdown-item" href="javascript:void(0);">Share</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="nav-align-top">
                            <ul class="nav nav-tabs nav-fill" role="tablist">
                                <li class="nav-item">
                                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#navs-justified-new" aria-controls="navs-justified-new"
                                        aria-selected="true">
                                        New
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#navs-justified-link-preparing"
                                        aria-controls="navs-justified-link-preparing" aria-selected="false">
                                        Preparing
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#navs-justified-link-shipping"
                                        aria-controls="navs-justified-link-shipping" aria-selected="false">
                                        Shipping
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content px-2 mx-1 pb-0">
                                <div class="tab-pane fade show active" id="navs-justified-new" role="tabpanel">
                                    <ul class="timeline mb-0 pb-1">
                                        <li class="timeline-item ps-4 border-left-dashed pb-1">
                                            <span class="timeline-indicator-advanced timeline-indicator-success">
                                                <i class="ti ti-circle-check"></i>
                                            </span>
                                            <div class="timeline-event px-0 pb-0">
                                                <div class="timeline-header">
                                                    <small class="text-success text-uppercase fw-medium">sender</small>
                                                </div>
                                                <h6 class="mb-1">Myrtle Ullrich</h6>
                                                <p class="text-muted mb-0">101 Boulder,
                                                    California(CA), 95959</p>
                                            </div>
                                        </li>
                                        <li class="timeline-item ps-4 border-transparent">
                                            <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                <i class="ti ti-map-pin"></i>
                                            </span>
                                            <div class="timeline-event px-0 pb-0">
                                                <div class="timeline-header">
                                                    <small class="text-primary text-uppercase fw-medium">Receiver</small>
                                                </div>
                                                <h6 class="mb-1">Barry Schowalter</h6>
                                                <p class="text-muted mb-0">939 Orange, California(CA),
                                                    92118</p>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="border-bottom border-bottom-dashed mt-0 mb-4"></div>
                                    <ul class="timeline mb-0">
                                        <li class="timeline-item ps-4 border-left-dashed pb-1">
                                            <span class="timeline-indicator-advanced timeline-indicator-success">
                                                <i class="ti ti-circle-check"></i>
                                            </span>
                                            <div class="timeline-event px-0 pb-0">
                                                <div class="timeline-header">
                                                    <small class="text-success text-uppercase fw-medium">sender</small>
                                                </div>
                                                <h6 class="mb-1">Veronica Herman</h6>
                                                <p class="text-muted mb-0">162 Windsor,
                                                    California(CA), 95492</p>
                                            </div>
                                        </li>
                                        <li class="timeline-item ps-4 border-transparent">
                                            <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                <i class="ti ti-map-pin"></i>
                                            </span>
                                            <div class="timeline-event px-0 pb-0">
                                                <div class="timeline-header">
                                                    <small class="text-primary text-uppercase fw-medium">Receiver</small>
                                                </div>
                                                <h6 class="mb-1">Helen Jacobs</h6>
                                                <p class="text-muted mb-0">487 Sunset, California(CA),
                                                    94043</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tab-pane fade" id="navs-justified-link-preparing" role="tabpanel">
                                    <ul class="timeline mb-0 pb-1">
                                        <li class="timeline-item ps-4 border-left-dashed pb-1">
                                            <span class="timeline-indicator-advanced timeline-indicator-success">
                                                <i class="ti ti-circle-check"></i>
                                            </span>
                                            <div class="timeline-event px-0 pb-0">
                                                <div class="timeline-header">
                                                    <small class="text-success text-uppercase fw-medium">sender</small>
                                                </div>
                                                <h6 class="mb-1">Barry Schowalter</h6>
                                                <p class="text-muted mb-0">939 Orange, California(CA),
                                                    92118</p>
                                            </div>
                                        </li>
                                        <li class="timeline-item ps-4 border-transparent">
                                            <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                <i class="ti ti-map-pin"></i>
                                            </span>
                                            <div class="timeline-event px-0 pb-0">
                                                <div class="timeline-header">
                                                    <small class="text-primary text-uppercase fw-medium">Receiver</small>
                                                </div>
                                                <h6 class="mb-1">Myrtle Ullrich</h6>
                                                <p class="text-muted mb-0">101 Boulder,
                                                    California(CA), 95959</p>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="border-bottom border-bottom-dashed mt-0 mb-4"></div>
                                    <ul class="timeline mb-0">
                                        <li class="timeline-item ps-4 border-left-dashed pb-1">
                                            <span class="timeline-indicator-advanced timeline-indicator-success">
                                                <i class="ti ti-circle-check"></i>
                                            </span>
                                            <div class="timeline-event px-0 pb-0">
                                                <div class="timeline-header">
                                                    <small class="text-success text-uppercase fw-medium">sender</small>
                                                </div>
                                                <h6 class="mb-1">Veronica Herman</h6>
                                                <p class="text-muted mb-0">162 Windsor,
                                                    California(CA), 95492</p>
                                            </div>
                                        </li>
                                        <li class="timeline-item ps-4 border-transparent">
                                            <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                <i class="ti ti-map-pin"></i>
                                            </span>
                                            <div class="timeline-event px-0 pb-0">
                                                <div class="timeline-header">
                                                    <small class="text-primary text-uppercase fw-medium">Receiver</small>
                                                </div>
                                                <h6 class="mb-1">Helen Jacobs</h6>
                                                <p class="text-muted mb-0">487 Sunset, California(CA),
                                                    94043</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-pane fade" id="navs-justified-link-shipping" role="tabpanel">
                                    <ul class="timeline mb-0 pb-1">
                                        <li class="timeline-item ps-4 border-left-dashed pb-1">
                                            <span class="timeline-indicator-advanced timeline-indicator-success">
                                                <i class="ti ti-circle-check"></i>
                                            </span>
                                            <div class="timeline-event px-0 pb-0">
                                                <div class="timeline-header">
                                                    <small class="text-success text-uppercase fw-medium">sender</small>
                                                </div>
                                                <h6 class="mb-1">Veronica Herman</h6>
                                                <p class="text-muted mb-0">101 Boulder,
                                                    California(CA), 95959</p>
                                            </div>
                                        </li>
                                        <li class="timeline-item ps-4 border-transparent">
                                            <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                <i class="ti ti-map-pin"></i>
                                            </span>
                                            <div class="timeline-event px-0 pb-0">
                                                <div class="timeline-header">
                                                    <small class="text-primary text-uppercase fw-medium">Receiver</small>
                                                </div>
                                                <h6 class="mb-1">Barry Schowalter</h6>
                                                <p class="text-muted mb-0">939 Orange, California(CA),
                                                    92118</p>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="border-bottom border-bottom-dashed mt-0 mb-4"></div>
                                    <ul class="timeline mb-0">
                                        <li class="timeline-item ps-4 border-left-dashed pb-1">
                                            <span class="timeline-indicator-advanced timeline-indicator-success">
                                                <i class="ti ti-circle-check"></i>
                                            </span>
                                            <div class="timeline-event px-0 pb-0">
                                                <div class="timeline-header">
                                                    <small class="text-success text-uppercase fw-medium">sender</small>
                                                </div>
                                                <h6 class="mb-1">Myrtle Ullrich</h6>
                                                <p class="text-muted mb-0">162 Windsor,
                                                    California(CA), 95492</p>
                                            </div>
                                        </li>
                                        <li class="timeline-item ps-4 border-transparent">
                                            <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                <i class="ti ti-map-pin"></i>
                                            </span>
                                            <div class="timeline-event px-0 pb-0">
                                                <div class="timeline-header">
                                                    <small class="text-primary text-uppercase fw-medium">Receiver</small>
                                                </div>
                                                <h6 class="mb-1">Helen Jacobs</h6>
                                                <p class="text-muted mb-0">487 Sunset, California(CA),
                                                    94043</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Orders by Countries -->

            <!-- On route vehicles Table -->

            <div class="col-12 order-5">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">
                                <i class="fa-solid fa-car-side"></i>
                                Commandes en cour...
                            </h5>
                        </div>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="routeVehicles" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="routeVehicles">
                                <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                <a class="dropdown-item" href="javascript:void(0);">Share</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-datatable table-responsive">
                        <table class=" table">
                            <thead class="border-top">
                                <tr>
                                    <th></th>
                                    <th>Article</th>
                                    <th>localisation</th>
                                    <th>Date de vente</th>
                                    <th>Gouvernorat</th>
                                    <th>Statut</th>
                                    <th class="w-20">progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($commandes_en_cour as $item)
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="alert alert-warning text-center">
                                            <i class="fa-solid fa-circle-info"></i>
                                            Aucune commande en cours
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!--/ On route vehicles Table -->
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
