@extends('Admin.fixe')
@section('titre', 'Commandes')
@section('content')


@section('body')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="col-lg-12 col-xxl-12 mb-4 order-5 order-xxl-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Liste Des Commandes</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form id="filter-form">
                        <div class="row mb-4 align-items-end">
                            <div class="col-md-4">
                                <label for="regionFilter" class="form-label">Filtrer par région</label>
                                <select name="region_id" class="form-select form-select-sm" id="regionFilter">
                                    <option value="">Toutes les régions</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>
                                            {{ $region->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="dateFilter" class="form-label">Filtrer par date</label>
                                <input name="date" type="date" value="{{ request('date') }}" class="form-control form-control-sm" id="dateFilter">
                            </div>

                            <div class="col-md-5 d-flex gap-2">
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-sm btn-primary">Appliquer</button>
                                </div>
                                <div class="mt-3">
                                    <button type="button" id="reset-btn" class="btn btn-sm btn-outline-secondary">Réinitialiser</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table w-100 table-custom">
                            <thead class="th-white">
                                <tr>
                                    <th>Vendeur</th>
                                    <th>Acheteur</th>
                                    <th>Article</th>
                                    <th>ID Expédition (Aramex)</th>
                                    <th>Frais Livraison</th>
                                    <th>État</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody id="commande-table-body">
                                @forelse ($commandes as $commande)
                                    <tr>
                                        <td>
                                            <a href="/admin/client/{{ $commande->vendor->id }}/view">
                                                    {{ $commande->vendor->username }}
                                            </a>
                                            <br>
                                            <small><b class="text-color2">Région:</b> {{ $commande->vendor->region_info->nom }}</small>
                                            <div>
                                                <span class="message-style" onclick="OpenModalMessage('{{ $commande->vendor->id }}','{{ $commande->vendor->username }}', '{{ $commande->post->titre }}', '{{ $commande->post->id }}', '{{ config('app.url').Storage::url($commande->post->photos[0]) }}')">
                                                    <i class="bi bi-chat-left-text-fill" style="margin-right: 5px;"></i> Message
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="/admin/client/{{ $commande->buyer->id }}/view">
                                                    {{ $commande->buyer->username }}
                                            </a>
                                            <br>
                                            <small><b class="text-color2">Région:</b> {{ $commande->buyer->region_info->nom }}</small>
                                            <div>
                                                <span class="message-style" onclick="OpenModalMessage('{{ $commande->buyer->id }}','{{ $commande->buyer->username }}', '{{ $commande->post->titre }}', '{{ $commande->post->id }}', '{{ config('app.url').Storage::url($commande->post->photos[0]) }}')">
                                                    <i class="bi bi-chat-left-text-fill" style="margin-right: 5px;"></i> Message
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="/admin/publication/{{ $commande->post->id }}/view">
                                                {{ $commande->post->titre }}
                                            </a>
                                        </td>
                                        <td>{{ $commande->shipment_id ?? '—' }}</td>
                                        <td>{{ $commande->frais_livraison }} <sup>DH</sup></td>
                                        <td>
                                            <span class="badge-etat
                                                @if($commande->post->statut === 'validation') etat-validation
                                                @elseif($commande->post->statut === 'vente') etat-vente
                                                @elseif($commande->post->statut === 'vendu') etat-vendu
                                                @elseif($commande->post->statut === 'livraison') etat-livraison
                                                @elseif($commande->post->statut === 'livré') etat-livre
                                                @elseif($commande->post->statut === 'refusé') etat-refuse
                                                @elseif($commande->post->statut === 'préparation') etat-preparation
                                                @elseif($commande->post->statut === 'en voyage') etat-en-voyage
                                                @elseif($commande->post->statut === 'en cours de livraison') etat-en-cours
                                                @elseif($commande->post->statut === 'ramassée') etat-ramassee
                                                @elseif($commande->post->statut === 'retourné') etat-retourne
                                                @endif">
                                                {{ $commande->post->statut }}
                                            </span>
                                        </td>

                                        <td>{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8">
                                            <div class="p-3">
                                                Aucune commande trouvée
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">{{ $commandes->links('pagination::bootstrap-4') }}</div>
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
    <script src="/assets-admin/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="/assets-admin/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="/assets-admin/js/main.js"></script>
    <script src="/assets-admin/js/app-logistics-dashboard.js"></script>
    <script>
    function getFiltersQueryString() {
        const form = document.getElementById('filter-form');
        return new URLSearchParams(new FormData(form)).toString();
    }

    function fetchAndReplace(urlWithParams) {
        fetch(urlWithParams, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const newTbody = doc.querySelector('#commande-table-body');
            const currentTbody = document.querySelector('#commande-table-body');
            if (newTbody && currentTbody) {
                currentTbody.innerHTML = newTbody.innerHTML;
            }

            const newPagination = doc.querySelector('.pagination');
            const currentPagination = document.querySelector('.pagination');
            if (newPagination && currentPagination) {
                currentPagination.innerHTML = newPagination.innerHTML;
                attachPaginationListeners();
            }
        });
    }

    document.getElementById('filter-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const baseUrl = "{{ route('orders') }}";
        const queryString = getFiltersQueryString();
        fetchAndReplace(`${baseUrl}?${queryString}`);
    });

    document.getElementById('reset-btn').addEventListener('click', function () {
        document.getElementById('regionFilter').value = '';
        document.getElementById('dateFilter').value = '';
        document.getElementById('filter-form').dispatchEvent(new Event('submit'));
    });

    function attachPaginationListeners() {
        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                const pageUrl = new URL(this.href);
                const page = pageUrl.searchParams.get('page');
                const query = getFiltersQueryString();
                const baseUrl = "{{ route('orders') }}";

                fetchAndReplace(`${baseUrl}?${query}&page=${page}`);
            });
        });
    }

    attachPaginationListeners();


</script>
@endsection
