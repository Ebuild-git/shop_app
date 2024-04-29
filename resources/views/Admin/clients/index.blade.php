@extends('Admin.fixe')
@section('title', 'Utilisateurs')
@section('content')



@section('body')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Utilisateur /</span> {{ $type }}</h4>

        @livewire('ListeUtilisateurs', ['type' => $type])
    </div>
    <!--/ Content -->


    <!-- Modal 1-->
    <div class="modal fade" id="MessageModal" aria-labelledby="modalToggleLabel" tabindex="-1" style="display: none"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalToggleLabel">
                        Envoyer un message à @<span id="destinataire">[distinataire]</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @livewire('Admin.SendMessage')
                </div>
            </div>
        </div>
    </div>




@endsection





@section('script')
    <script>
        function OpenModalMessage(email, username) {
            Livewire.dispatch('sendDataUser', {
                username: username,
                email: email
            });
            $("#destinataire").html(username);
            $('#MessageModal').modal('show');
        }
    </script>


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
