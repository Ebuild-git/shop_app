@extends('Admin.fixe')
@section('titre', $user->username . ' - Signalements')
@section('content')



@section('body')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card p-4">
            <h1 class="h4 mb-4">
                Liste des signalements ({{ $user->username }})
            </h1>
            <div class="row">
                @forelse ($user->violations as $violation)
                    <div class="col-sm-4 mb-3">
                        <div class="card p-3 shadow-sm hover-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="text-danger">
                                        <i class="bi bi-exclamation-octagon"></i>
                                        {{ $violation->type }}
                                    </span>
                                </div>
                                <div class="small text-muted">
                                    <div>Auteur</div>
                                    <a href="/admin/client/{{$violation->auteur->id}}/view" class="text-decoration-none">
                                        <i class="bi bi-person-circle"></i>
                                        {{ '@'. $violation->auteur->username }}
                                    </a>
                                </div>
                            </div>

                            <p class="text-muted">{{ $violation->message }}</p>

                            @if ($violation->post)
                                <div class="post-info mt-2">
                                    <h5 class="mt-1">{{ $violation->post->titre }}</h5>
                                    @if (!empty($violation->post->photos) && count($violation->post->photos) > 0)
                                        @foreach ($violation->post->photos as $key => $image)
                                            @if ($key == 0) <!-- Show only the first image -->
                                                <div class="image-cell mb-2">
                                                    <a href="{{ url('/admin/publication/' . $violation->post->id . '/view') }}">
                                                        <img src="{{ Storage::url($image) }}" alt="{{ $violation->post->titre }} - Image 1" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                                                    </a>
                                                </div>
                                            @endif
                                            @break <!-- Exit after the first iteration -->
                                        @endforeach
                                    @endif
                                </div>
                            @endif

                            <div class="modal-footer border-0">
                                <span class="small text-muted">
                                    {{ $violation->created_at }}
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
    .table-image {
        width: 100%;
        height: auto;
        border-radius: 10px;
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
