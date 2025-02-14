@extends('Admin.fixe')
@section('titre', $post->titre . ' - Signalements')
@section('content')



@section('body')

    <div class="container-xxl flex-grow-1 container-p-y">
        {{-- <h5 class="py-3 mb-4">
            <span class="text-muted fw-light text-capitalize">{{ $post->titre }}</span>
        </h5> --}}

        <div class="card p-4 shadow-sm">
            <h1 class="h4 mb-4">
                Liste des signalements
            </h1>
            <div class="row">
                @forelse ($post->signalements as $signalement)
                    <div class="col-sm-4 mb-3">
                        <div class="card p-3 shadow-sm hover-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="text-danger">
                                        <i class="bi bi-exclamation-octagon"></i>
                                        {{ $signalement->type }}
                                    </span>
                                </div>
                                <div class="small text-muted text-end">
                                    <div>Auteur</div>
                                    <a href="/admin/client/{{$signalement->auteur->id}}/view" class="text-decoration-none">
                                        <i class="bi bi-person-circle"></i>
                                        {{ '@'. $signalement->auteur->username }}
                                    </a>
                                </div>
                            </div>

                            <p class="text-muted">{{ $signalement->message }}</p>

                            <div class="modal-footer border-0">
                                <span class="small text-muted">
                                    {{ $signalement->created_at }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted">Aucun signalement trouvé.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
    .hover-card {
        transition: transform 0.2s;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    </style>

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
