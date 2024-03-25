@extends('Admin.fixe')
@section('titre', 'Paramètres')
@section('content')



@section('body')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Paramètres /</span> Account</h4>

        <div class="row">
          <div class="col-md-12">
            <ul class="nav nav-pills flex-column flex-md-row mb-4">
              <li class="nav-item">
                <a class="nav-link active" href="{{ route('admin_settings')}}"
                  ><i class="ti-xs ti ti-users me-1"></i> Account</a
                >
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('admin_settings_security')}}"
                  ><i class="ti-xs ti ti-lock me-1"></i> Security</a
                >
              </li>
            </ul>
            <div class="card mb-4">
              <h5 class="card-header">Profile Details</h5>
              <!-- Account -->
              <div class="card-body">
                @livewire('Admin.UpdateInformations')
              
              </div>
              <!-- /Account -->
            </div>
          </div>
        </div>
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
