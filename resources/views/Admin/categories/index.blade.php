@extends('Admin.fixe')
@section('title', 'Catégories')
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
                            <h5 class="card-header">Liste des catégories</h5>
                        </div>
                        <div class="my-auto">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addcategorie">
                                <i class="bi bi-plus"></i> Catégorie
                            </button>
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




    <!-- Add New Credit Card Modal -->
    <div class="modal fade" id="addsouscategorie" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2">sous-catégorie</h3>
                        <p class="text-muted">Création d'une nouvelle sous-catégorie</p>
                    </div>
                    @livewire('CreateSousCategorie')
                </div>
            </div>
        </div>
    </div>
    <!--/ Add New Credit Card Modal -->


     <!-- Add New Credit Card Modal -->
     <div class="modal fade" id="addcategorie" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2">Catégorie</h3>
                        <p class="text-muted">Création d'une nouvelle catégorie</p>
                    </div>
                    @livewire('FormCreateCategorie')
                </div>
            </div>
        </div>
    </div>
    <!--/ Add New Credit Card Modal -->

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
