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
            <div class="col-sm-8">
                <div class="table-responsive text-nowrap card">
                    <h5 class="card-header">Liste des catégories</h5>
                    <table class="table">
                      <thead class="table-dark">
                        <tr>
                          <td></td>
                          <th>Titre</th>
                          <th>description</th>
                          <th>Publications</th>
                          <th>Création</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                    @livewire('ListeCategorieAdmin')
                    </table>
                  </div>
            </div>
            <div class="col-sm-4">
                <div class="card p-3">
                @livewire('FormCreateCategorie')
            </div>
            </div>
          </div>
          <br><br>
        </div>
        <!--/ Bootstrap Table with Header Dark -->

        <hr class="my-5" />

    
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
