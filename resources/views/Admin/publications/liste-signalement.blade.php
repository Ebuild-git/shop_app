@extends('Admin.fixe')
@section('titre', $post->titre . ' - Signalements')
@section('content')



@section('body')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light text-capitalize">{{ $post->titre }}</span>
        </h4>

        <div class="card p-3">
            <h1 class="h4">
                Liste des signalements
            </h1>
            <div class="row">
                @forelse ($post->signalements as $signalement)
                    <div class="col-sm-4">
                        <div class="card p-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <span class="text-danger">
                                        <i class="bi bi-exclamation-octagon"></i>
                                        {{ $signalement->type }}
                                    </span>
                                </div>
                                <div>
                                    <div class="small">
                                        Auteur
                                    </div>
                                    <a href="/admin/client/{{$signalement->auteur->id}}/view">
                                        <i class="bi bi-person-circle"></i>
                                        {{ '@'. $signalement->auteur->username }}
                                    </a>
                                </div>
                            </div>

                            <p>
                                {{ $signalement->message }}
                            </p>
                            <div class="modal-footer">
                                <span class="small text-muted">
                                    {{ $signalement->created_at }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
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
