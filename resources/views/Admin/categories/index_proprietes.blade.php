@extends('Admin.fixe')
@section('titre', 'Propriété des articles')
@section('content')



@section('body')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Propriétés /</span> Gestion des Propriétés</h4>
        <!-- Bootstrap Table with Header - Dark -->
        <div class=" container">
            <div class="row">
                <div class="col-sm-12 card">
                    <div class="d-flex justify-content-between ">
                        <div>
                            <h5 class="card-header">Liste des Propriétés({{$totalProprietes}})</h5>
                        </div>
                        <div class="card-header">
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#add">
                                Ajouter une propriété
                            </button>
                        </div>
                    </div>
                    <div class="nav-align-top mb-4">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="navs-pills-justified-home" role="tabpanel">
                                @livewire('Admin.Proprietes')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br><br>
        </div>
        <!--/ Bootstrap Table with Header Dark -->


        <div class="modal fade" id="add" aria-labelledby="modalToggleLabel" tabindex="-1"
            style="display: none" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalToggleLabel">
                            Nouvelle propriété
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @livewire('Admin.FormCreatePropriete')
                    </div>
                </div>
            </div>
        </div>






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
