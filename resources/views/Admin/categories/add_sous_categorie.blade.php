@extends('Admin.fixe')
@section('titre', 'Sous Catégories de ' . $categorie->titre)
@section('content')



@section('body')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">
                Sous-catégories /
            </span>
            {{ $categorie->titre }}
        </h4>
        <!-- Bootstrap Table with Header - Dark -->
        <div class="container">
            <div class="row">
                <div class="col-sm-12 card">
                    <div class="p-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5>Sous-catégorie de "{{ $categorie->titre }}"</h5>
                            </div>
                            <div>
                                <a href="/admin/categorie">
                                    <button class="btn  btn-dark">
                                        Retour
                                    </button>
                                </a>
                                <a href="{{ route('gestion_proprietes') }}">
                                    <button class="btn btn-info  me-sm-3 me-1 waves-effect waves-light">
                                        <i class="bi bi-plus"></i> Ajouter une propriété
                                    </button>
                                </a>
                                <button class="btn btn-primary  me-sm-3 me-1 waves-effect waves-light" data-bs-toggle="modal"
                                data-bs-target="#modalToggle-new-sous-cat">
                                    <i class="bi bi-plus"></i> Ajouter une sous-categorie
                                </button>
                            </div>
                        </div>
                    </div>
                    @livewire('CreateSousCategorie', ['id_categorie' => $categorie->id])
                    <br><br>
                </div>
            </div>
        </div>
    </div>
    <!--/ Content -->





    <!-- Modal 1-->
    <div class="modal fade" id="modalToggle-new-sous-cat" aria-labelledby="modalToggleLabel" tabindex="-1"
        style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-xl  modal-pricing modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalToggleLabel">
                        Nouvelle sous-catégorie
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @livewire('FormCreateSousCategorie', ['id_categorie' => $categorie->id])
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
