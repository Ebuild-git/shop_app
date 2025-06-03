@extends('Admin.fixe')
@section('titre', $user->username . ' - Signalements')
@section('content')



@section('body')
    <!--/ Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
    <div class="card border-0 shadow-sm rounded-3 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h5 fw-bold text-dark mb-0">
                <i class="bi bi-shield-exclamation text-danger me-2"></i>Historique des violations ({{ $user->username }})
            </h2>

            <form method="POST" action="{{ route('violations.deleteAll') }}">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash3 me-1"></i> Supprimer tout
                </button>
            </form>
        </div>

        <div class="row">
            @forelse ($signalements as $violation)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm hover-shadow transition p-3 rounded-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="text-danger fw-semibold">
                                <i class="bi bi-exclamation-octagon me-1"></i>{{ $violation->type }}
                            </span>
                            <div class="text-muted text-end small">
                                <div class="fw-semibold">Auteur</div>
                                <a href="/admin/client/{{ $violation->auteur->id }}/view" class="text-decoration-none text-primary">
                                    <i class="bi bi-person-circle me-1"></i>{{ $violation->auteur->username }}
                                </a>
                            </div>
                        </div>

                        <p class="text-secondary small mb-3">{{ $violation->message }}</p>

                        @if ($violation->post)
                            <div class="mb-3">
                                <h6 class="fw-semibold">{{ $violation->post->titre }}</h6>
                                @if (!empty($violation->post->photos) && count($violation->post->photos) > 0)
                                    @foreach ($violation->post->photos as $key => $image)
                                        @if ($key == 0)
                                            <a href="{{ url('/admin/publication/' . $violation->post->id . '/view') }}">
                                                <img src="{{ Storage::url($image) }}" alt="{{ $violation->post->titre }}" class="img-fluid rounded-3 shadow-sm" style="max-height: 200px; object-fit: cover;">
                                            </a>
                                        @endif
                                        @break
                                    @endforeach
                                @endif
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center small text-muted mt-auto">
                            <span>{{ $violation->created_at->translatedFormat('d F Y H:i')  }}</span>
                            <form method="POST" action="{{ route('violations.destroy', $violation->id) }}" class="mb-0 ms-2">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-link text-danger p-0" title="Supprimer">
                                    <i class="bi bi-trash3 fs-5"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted fs-6">Aucun signalement trouvé.</p>
                </div>
            @endforelse
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
