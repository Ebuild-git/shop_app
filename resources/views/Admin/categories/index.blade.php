@extends('admin.fixe')
@section('title', 'Catégories')
@section('content')



@section('body')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Catégories /</span> Gestion des Catégories</h4>
        <!-- Bootstrap Table with Header - Dark -->
        <div class=" container">
            <div class="row">
                <div class="col-sm-8 card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-header">Liste des catégories</h5>
                        </div>
                        <div class="my-auto">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addsouscategorie">
                                <i class="bi bi-plus"></i> Sous Catégorie
                            </button>
                        </div>
                    </div>
                    <div class="nav-align-top mb-4">
                        <ul class="nav nav-pills mb-3 nav-fill" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home"
                                    aria-selected="true">
                                    <i class="bi bi-grid-3x3 tf-icons"></i> &nbsp;  Catégories
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-pills-justified-profile"
                                    aria-controls="navs-pills-justified-profile" aria-selected="false">
                                    <i class="bi bi-ui-checks-grid"></i> &nbsp; Sous-catégories
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="navs-pills-justified-home" role="tabpanel">
                                <div class="table-responsive text-nowrap ">
                                    <table class="table">
                                        <thead class="table-dark">
                                            <tr>
                                                <td></td>
                                                <th>Titre</th>
                                                <th>sous-catégories</th>
                                                <th>Publications</th>
                                                <th>Création</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        @livewire('ListeCategorieAdmin')
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="navs-pills-justified-profile" role="tabpanel">
                              <div class="table-responsive text-nowrap ">
                                <table class="table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Titre</th>
                                            <th>Publications</th>
                                            <th>Création</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    @livewire('ListeSousCategories')
                                </table>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="col-sm-12">
                        <div class="card p-3">
                            @livewire('FormCreateCategorie')
                        </div>
                    </div>
                </div>
            </div>
            <br><br>
        </div>
        <!--/ Bootstrap Table with Header Dark -->

        <hr class="my-5" />


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
