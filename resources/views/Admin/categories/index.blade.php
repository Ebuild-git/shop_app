@extends('Admin.fixe')
@section('titre', 'Catégories')
@section('content')



@section('body')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Catégories /</span> Gestion des Catégories</h4>
        <!-- Bootstrap Table with Header - Dark -->
        <div class=" container">
            <div class="row">
                <div class="col-sm-12 card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-header">Liste des catégories({{ $totalCategories }})</h5>
                        </div>

                        <div class="my-auto">
                            <a href="{{ route('grille_prix') }}">
                                <button class="btn btn-dark btn-sm me-sm-3 me-1 waves-effect waves-light">
                                    <i class="bi bi-grid-3x3"></i> &nbsp; grille de prix
                                </button>
                            </a>
                            <a href="{{ route('add_categorie') }}">
                                <button class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus"></i> &nbsp; Catégorie
                                </button>
                            </a>
                        </div>
                    </div>
                    <div class="nav-align-top mb-4">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="navs-pills-justified-home" role="tabpanel">
                                @livewire('ListeCategorieAdmin')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br><br>
        </div>
        <!--/ Bootstrap Table with Header Dark -->
    </div>
    <!--/ Content -->






@endsection

@section('script')
    <script src="/assets-admin/vendor/libs/jquery/jquery.js"></script>
    <script src="/assets-admin/vendor/libs/popper/popper.js"></script>
    <script src="/assets-admin/vendor/js/bootstrap.js"></script>
    <script src="/assets-admin/vendor/libs/node-waves/node-waves.js"></script>
    <script src="/assets-admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="/assets-admin/vendor/libs/hammer/hammer.js"></script>
    <script src="/assets-admin/vendor/libs/i18n/i18n.js"></script>
    <script src="/assets-admin/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="/assets-admin/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="/assets-admin/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="/assets-admin/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>

    <!-- Main JS -->
    <script src="/assets-admin/js/main.js"></script>

    <!-- Page JS -->
    <script src="/assets-admin/js/app-logistics-dashboard.js"></script>
@endsection
