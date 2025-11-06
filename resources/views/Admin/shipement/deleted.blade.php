@extends('Admin.fixe')
@section('titre', 'Commandes Supprimées')
@section('content')

@section('body')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="col-lg-12 col-xxl-12 mb-4 order-5 order-xxl-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Liste Des Commandes Supprimées</h5>
                </div>
            </div>
            <div class="card-body">
                <form id="filter-form">
                    <div class="row mb-4 align-items-end g-2">
                        <div class="col-auto">
                            <label for="searchFilter" class="form-label">Rechercher</label>
                            <input type="text" name="search" id="searchFilter" class="form-control form-control-sm"
                                value="{{ request('search') }}" placeholder="ID commande, ID expédition, nom vendeur/acheteur">
                        </div>

                        <div class="col-auto">
                            <label for="regionFilter" class="form-label">Région</label>
                            <select name="region_id" class="form-select form-select-sm" id="regionFilter">
                                <option value="">Toutes les régions</option>
                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>
                                        {{ __($region->nom ?? '—') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-auto">
                            <label for="dateFilter" class="form-label">Date</label>
                            <input name="date" type="date" value="{{ request('date') }}" class="form-control form-control-sm" id="dateFilter">
                        </div>

                        <div class="col-auto d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-sm btn-primary">Appliquer</button>
                            <button type="button" id="reset-btn" class="btn btn-sm btn-outline-secondary">Réinitialiser</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table w-100 table-custom">
                        <thead class="th-white">
                            <tr>
                                <th>ID Commande</th>
                                <th>Vendeur</th>
                                <th>Acheteur</th>
                                <th>État de compte</th>
                                <th>Article</th>
                                <th>ID Expédition (Aramex)</th>
                                <th>Frais Livraison</th>
                                <th>État</th>
                                <th>Statut</th>
                                <th>Date de suppression</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="commande-table-body">
                            @forelse ($orders as $order)
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td>CMD-{{ $order->id }}</td>

                                        <td>
                                            @if($item->vendor)
                                                <a href="/admin/client/{{ $item->vendor->id }}/view">
                                                    {{ $item->vendor->username ?? '—' }}
                                                </a>
                                                <br>
                                                <small>
                                                    <b class="text-color2">Région:</b> {{ $item->vendor->region_info->nom ?? '/' }}
                                                </small>
                                                <div>
                                                        @if($item->vendor->deleted_at))
                                                        <span class="text-danger">(Utilisateur supprimé)</span>
                                                        @else
                                                            <span class="message-style"
                                                                onclick="OpenModalMessage(
                                                                    '{{ $item->vendor->id ?? 0 }}',
                                                                    '{{ $item->vendor->username ?? '—' }}',
                                                                    '{{ $item->post->titre ?? '—' }}',
                                                                    '{{ $item->post->id ?? 0 }}',
                                                                    '{{ $item->post->photos[0] ? config('app.url') . Storage::url($item->post->photos[0]) : asset('assets-admin/img/no-image.png') }}'
                                                                )">
                                                                <i class="bi bi-chat-left-text-fill" style="margin-right: 5px;"></i> Message
                                                            </span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($order->buyer)
                                                <a href="/admin/client/{{ $order->buyer->id }}/view">
                                                    {{ $order->buyer->username ?? '—' }}
                                                </a>
                                                <br>
                                                <small>
                                                    <b class="text-color2">Région:</b> {{ $order->buyer->region_info->nom ?? '/' }}
                                                </small>
                                                <div>
                                                    @if( $order->buyer->deleted_at)
                                                        <span class="text-danger">(Utilisateur supprimé)</span>
                                                    @else
                                                    <span class="message-style"
                                                        onclick="OpenModalMessage(
                                                            '{{ $order->buyer->id ?? 0 }}',
                                                            '{{ $order->buyer->username ?? '—' }}',
                                                            '{{ $item->post->titre ?? '—' }}',
                                                            '{{ $item->post->id ?? 0 }}',
                                                            '{{ $item->post->photos[0] ? config('app.url') . Storage::url($item->post->photos[0]) : asset('assets-admin/img/no-image.png') }}'
                                                        )">
                                                        <i class="bi bi-chat-left-text-fill" style="margin-right: 5px;"></i> Message
                                                    </span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $acheteurSupprime = $order->buyer?->deleted_at ? true : false;
                                                $vendeurSupprime = $item->vendor?->deleted_at ? true : false;
                                            @endphp

                                            @if(!$acheteurSupprime && !$vendeurSupprime)
                                                <span style="font-size: 1.2rem;">🟢</span> OK
                                            @elseif($acheteurSupprime && !$vendeurSupprime)
                                                <span style="font-size: 1.2rem;">🔴</span> Acheteur supprimé
                                            @elseif(!$acheteurSupprime && $vendeurSupprime)
                                                <span style="font-size: 1.2rem;">🟠</span> Vendeur supprimé
                                            @else
                                                <span style="font-size: 1.2rem;">🔴🟠</span> Les deux supprimés
                                            @endif
                                        </td>

                                        <td>
                                            @if($item->post)
                                                <a href="/admin/publication/{{ $item->post->id }}/view">
                                                    {{ $item->post->titre ?? '—' }}
                                                </a>
                                            @else
                                                <span class="text-muted">Post supprimé</span>
                                            @endif
                                        </td>

                                        <td>{{ $item->shipment_id ?? '—' }}</td>

                                        <td>{{ $item->delivery_fee ?? 0 }} <sup>DH</sup></td>

                                        <td>
                                            @php $statut = $item->post?->statut ?? '—'; @endphp
                                            <span class="badge-etat
                                                @if($statut === 'validation') etat-validation
                                                @elseif($statut === 'vente') etat-vente
                                                @elseif($statut === 'vendu') etat-vendu
                                                @elseif($statut === 'livraison') etat-livraison
                                                @elseif($statut === 'livré') etat-livre
                                                @elseif($statut === 'refusé') etat-refuse
                                                @elseif($statut === 'préparation') etat-preparation
                                                @elseif($statut === 'en voyage') etat-en-voyage
                                                @elseif($statut === 'en cours de livraison') etat-en-cours
                                                @elseif($statut === 'ramassée') etat-ramassee
                                                @elseif($statut === 'retourné') etat-retourne
                                                @endif">
                                                {{ $statut }}
                                            </span>
                                        </td>

                                        <td>
                                            @switch($order->status)
                                                @case('pending')
                                                    <span class="badge bg-secondary">Crée</span>
                                                    @break
                                                @case('expédiée')
                                                    <span class="badge bg-info text-dark">Expédiée</span>
                                                    @break
                                                @case('livrée')
                                                    <span class="badge bg-success">Livrée</span>
                                                    @break
                                                @case('annulée')
                                                    <span class="badge bg-danger">Annulée</span>
                                                    @break
                                                @case('supprimée')
                                                    <span class="badge bg-danger">Supprimée</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-light text-dark">{{ ucfirst($order->status) }}</span>
                                            @endswitch
                                        </td>

                                        <td>{{ $order->deleted_at ? $order->deleted_at->format('d/m/Y H:i') : '—' }}</td>

                                        <td>
                                            <button class="btn btn-sm btn-outline-success mt-1"
                                                onclick="restoreOrder({{ $order->id }})">
                                                <i class="bi bi-arrow-counterclockwise"></i> Restaurer
                                            </button>

                                            <button class="btn btn-sm btn-outline-danger mt-1"
                                                onclick="confirmForceDelete({{ $order->id }})">
                                                <i class="bi bi-trash"></i> Supprimer définitivement
                                            </button>

                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="10">
                                        <div class="p-3">Aucune commande trouvée</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-3">{{ $orders->links('pagination::bootstrap-4') }}</div>
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
    fetch(urlWithParams, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
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
    const baseUrl = "{{ route('admin.orders.deleted') }}";
    const queryString = getFiltersQueryString();
    fetchAndReplace(`${baseUrl}?${queryString}`);
});

document.getElementById('reset-btn').addEventListener('click', function () {
    document.getElementById('regionFilter').value = '';
    document.getElementById('dateFilter').value = '';
    document.getElementById('searchFilter').value = '';
    document.getElementById('filter-form').dispatchEvent(new Event('submit'));
});

function attachPaginationListeners() {
    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const pageUrl = new URL(this.href);
            const page = pageUrl.searchParams.get('page');
            const query = getFiltersQueryString();
            const baseUrl = "{{ route('admin.orders.deleted') }}";
            fetchAndReplace(`${baseUrl}?${query}&page=${page}`);
        });
    });
}

attachPaginationListeners();
</script>
<script>
    function restoreOrder(orderId) {
        Swal.fire({
            title: "Restaurer cette commande ?",
            text: "Elle sera de nouveau active dans le système.",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Oui, restaurer",
            cancelButtonText: "Annuler",
            confirmButtonColor: "#198754",
            cancelButtonColor: "#6c757d"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/orders/${orderId}/restore`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        "Accept": "application/json"
                    }
                }).then(res => res.json())
                  .then(data => {
                      Swal.fire("Restauré!", "La commande a été restaurée.", "success")
                          .then(() => location.reload());
                  }).catch(err => {
                      Swal.fire("Erreur", "Une erreur est survenue.", "error");
                  });
            }
        });
    }
    function confirmForceDelete(orderId) {
        Swal.fire({
            title: "Êtes-vous sûr ?",
            text: "Cette action supprimera définitivement la commande !",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Oui, supprimer",
            cancelButtonText: "Annuler",
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/orders/${orderId}/force-delete`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        "Accept": "application/json"
                    }
                }).then(res => res.json())
                .then(data => {
                    Swal.fire("Supprimé!", "La commande a été supprimée définitivement.", "success")
                        .then(() => location.reload());
                }).catch(err => {
                    Swal.fire("Erreur", "Une erreur est survenue.", "error");
                });
            }
        });
    }
</script>


@endsection
