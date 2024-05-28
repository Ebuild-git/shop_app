@extends('Admin.fixe')
@section('titre', 'Publications signalées')
@section('content')



@section('body')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Publications signalées</span></h4>

        <div class="card">
            <div class="row p-2">
                <div class="col-sm-6 my-auto">
                    <h5 class="card-header">
                        Publications signalées
                    </h5>
                </div>
                <div class="col-sm-6 my-auto">
                    <form method="POST" action="{{ route('filtre_signalement') }}">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="date" class="form-control" name="date" value="{{ $date ? $date : null }}" required placeholder="titre">
                            <button class="btn btn-primary" type="submit" >
                                <i class="fa-solid fa-filter"></i> &nbsp;
                                Filtrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-datatable text-nowrap">
                <table class="datatables-ajax table">
                    <thead class="table-dark">
                        <tr>
                            <th></th>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Signalements</th>
                            <th></th>
                        </tr>
                    </thead>
       
                    <tbody>
                        @forelse ($posts as $post)
                            <tr>
                                <td class="avatar">
                                    <div class="avatar me-3">
                                        <img src="{{ $post->user_info->getAvatar() }}" alt="..." class="rounded-circle">
                                    </div>
                                </td>
                                <td>
                                    {{ $post->titre }}
                                    <div class="small">
                                        <i class="bi bi-heart-fill text-danger"></i> {{ $post->getLike->count() }} likes
                                    </div>
                                </td>
                                <td>
                                    <a href="/admin/client/{{ $post->user_info->id }}/view">
                                        {{ $post->user_info->username }}
                                    </a>
                                </td>
                                <td>
                                    <b class="text-danger">
                                        <i class="bi bi-exclamation-octagon"></i>
                                        {{ $post->signalements_count }}
                                    </b>
                                </td>
                                <td style="text-align: right;">
                                    <a href="/admin/publication/{{ $post->id }}/view">
                                        <button class="btn btn-sm btn-primary">
                                            Voir la publication
                                        </button>
                                    </a>
                                    <a href="/admin/post/{{ $post->id }}/signalement">
                                        <button class="btn btn-sm btn-dark">
                                            Voir les sginalements
                                        </button>
                                    </a>
                                    <button class="btn btn-sm btn-danger" onclick="toggle_confirmation({{ $post->id }})">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                    <a class="btn btn-sm btn-success d-none" id="confirmBtn{{ $post->id }}" href="{{ route('delete_signalement',['id'=> $post->id]) }}">
                                        <i class="bi bi-check-circle"></i>
                                        &nbsp;
                                        Confirmer
                                    </a>
                                </td>
       
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="p-3 text-center text-primary">
                                       <img width="80" height="80" src="https://img.icons8.com/dotty/80/008080/break.png" alt="break"/>
                                       <br>
                                        Aucune publication trouvé 
                                        @if ($date)
                                         a la date <b>{{ $date }}</b>
                                        @endif
                                        !
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-3" {{ $posts->links('pagination::bootstrap-4') }} </div>
                </div>
       
       
       
       
                <script>
                    function toggle_confirmation(productId) {
                        const confirmBtn = document.getElementById('confirmBtn' + productId);
                        if (!confirmBtn.classList.contains('d-none')) {
                            confirmBtn.classList.add('d-none');
                        } else {
                            // Masquer tous les autres boutons de confirmation s'ils sont visibles
                            document.querySelectorAll('.confirm-btn').forEach(btn => {
                                if (!btn.classList.contains('d-none')) {
                                    btn.classList.add('d-none');
                                }
                            });
                            confirmBtn.classList.remove('d-none');
                        }
                    }
                </script>
       
       
            </div>
            <!--/ Ajax Sourced Server-side -->
       
        
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
