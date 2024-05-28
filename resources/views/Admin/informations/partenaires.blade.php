@extends('Admin.fixe')
@section('titre', 'Enregistremen des partenaires')
@section('content')



@section('body')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="py-3 mb-4">
            <span class="text-muted fw-light">Configuration /</span> Nos partenaires
        </h5>
        <div class="card p-2">
            <div class="row">
                <div class="col-sm-8">
                    <div class="p-2">
                        <div class="row">
                            @forelse ($logos as $logo_save)
                                <div class="col-sm-3 col-6 card p-2">
                                    <form action="{{ route('delete_partenaires') }}" method="POST">
                                        <input type="hidden" name="url" value="{{ $logo_save }}">
                                        @csrf
                                        <div class="list-logo">
                                            <div class="logo">
                                                <img src="{{ Storage::url($logo_save) }}" alt="logo" srcset="">
                                            </div>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-sm w-100 small text-danger">
                                                <i class="bi bi-trash3"></i> Supprimer
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @empty
                                <div class="col-sm-12 p-5 text-center">
                                    <img width="50" height="50"
                                        src="https://img.icons8.com/ios/50/008080/not-showing-video-frames.png"
                                        alt="not-showing-video-frames" />
                                    <div>
                                        Aucun logo disponible pour l'instant;
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <form method="POST" action="{{ route('create_partenaires') }}" enctype="multipart/form-data">
                        @csrf
                        <h5>
                            Enregistrement d'un partenaire
                        </h5>
                        @include('components.alert-livewire')
                        <div class="mb-3">
                            <label for="">
                                Image du logo
                            </label>
                            <input type="file" required class="form-control" name="logo">
                            @error('logo')
                                <span class="small text-danger">{{ $message }}</span>
                            @enderror
                            <br>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Enregistrer
                                </button>
                            </div>
                        </div>
                    </form>
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
