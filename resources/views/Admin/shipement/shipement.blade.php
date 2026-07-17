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
                                <th>Statut livraison</th>
                                <th>Statut Client</th>
                                <th>Date</th>
                                <th>Note</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="commande-table-body">
                            @forelse ($orders as $order)
                                @php $shownAramexVendors = []; @endphp
                                @foreach ($order->items as $item)

                                    @php
                                        $postImage = $item->post?->photos[0] ?? null
                                            ? config('app.url') . Storage::url($item->post->photos[0])
                                            : asset('assets-admin/img/no-image.png');

                                        $postTitle = $item->post?->titre ?? '—';
                                        $postId = $item->post?->id ?? 0;
                                        $vendorId = $item->vendor?->id ?? 0;
                                        $aramexAlreadyShown = in_array($vendorId, $shownAramexVendors);

                                        // Check if this vendor has any unsynced item in this order
                                        $vendorHasUnsynced = $order->items
                                            ->where('vendor_id', $vendorId)
                                            ->whereNull('shipment_id')
                                            ->isNotEmpty();

                                        $vendorAllSynced = $order->items
                                            ->where('vendor_id', $vendorId)
                                            ->whereNotNull('shipment_id')
                                            ->count() === $order->items->where('vendor_id', $vendorId)->count();

                                    @endphp

                                    <tr>
                                        <td>CMD-{{ $order->id }}</td>

                                        <td>
                                            @if($item->vendor)
                                                <a href="/admin/client/{{ $item->vendor->id }}/view">
                                                    @if(!$item->vendor->deleted_at)
                                                        {{ $item->vendor->username }}
                                                    @else
                                                        {{ $item->vendor->username_deleted }}
                                                    @endif
                                                </a>
                                                <br><small class="text-muted">{{ 'U' . ($item->vendor->id + 1000) }}</small>

                                                <br>

                                                <small>
                                                    <b class="text-color2">Région:</b>
                                                    {{ $item->vendor->region_info->nom ?? '/' }}
                                                </small>

                                                <div>
                                                    @if($item->vendor->deleted_at)
                                                        <span class="text-danger">(Utilisateur supprimé)</span>
                                                    @else
                                                        <span class="message-style"
                                                            onclick="OpenModalMessage(
                                                                '{{ $item->vendor->id ?? 0 }}',
                                                                '{{ $item->vendor->username ?? '—' }}',
                                                                '{{ $postTitle }}',
                                                                '{{ $postId }}',
                                                                '{{ $postImage }}'
                                                            )">
                                                            <i class="bi bi-chat-left-text-fill" style="margin-right:5px;"></i>
                                                            Message
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
                                                    @if(!$order->buyer->deleted_at)
                                                        {{ $order->buyer->username }}
                                                    @else
                                                        {{ $order->buyer->username_deleted }}
                                                    @endif
                                                </a>

                                                <br><small class="text-muted">{{ 'U' . ($order->buyer->id + 1000) }}</small>
                                                <br>

                                                <small>
                                                    <b class="text-color2">Région:</b>
                                                    {{ $order->buyer->region_info->nom ?? '/' }}
                                                </small>

                                                <div>
                                                    @if($order->buyer->deleted_at)
                                                        <span class="text-danger">(Utilisateur supprimé)</span>
                                                    @else
                                                        <span class="message-style"
                                                            onclick="OpenModalMessage(
                                                                '{{ $order->buyer->id ?? 0 }}',
                                                                '{{ $order->buyer->username ?? '—' }}',
                                                                '{{ $postTitle }}',
                                                                '{{ $postId }}',
                                                                '{{ $postImage }}'
                                                            )">
                                                            <i class="bi bi-chat-left-text-fill" style="margin-right:5px;"></i>
                                                            Message
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
                                                <span style="font-size:1.2rem;">🟢</span> OK
                                            @elseif($acheteurSupprime && !$vendeurSupprime)
                                                <span style="font-size:1.2rem;">🔴</span> Acheteur supprimé
                                            @elseif(!$acheteurSupprime && $vendeurSupprime)
                                                <span style="font-size:1.2rem;">🟠</span> Vendeur supprimé
                                            @else
                                                <span style="font-size:1.2rem;">🔴🟠</span> Les deux supprimés
                                            @endif
                                        </td>

                                        <td>
                                            @if($item->post)
                                                <a href="/admin/publication/{{ $item->post->id }}/view">
                                                    {{ $item->post->titre }}
                                                </a>
                                                <br>
                                                <small class="text-muted">P{{ $item->post->id }}</small>
                                                @else
                                                <span class="text-muted">Post supprimé</span>
                                            @endif
                                        </td>

                                        <td>{{ $item->shipment_id ?? '—' }}</td>

                                        <td>
                                            {{ $item->delivery_fee ?? 0 }}
                                            <sup>DH</sup>
                                        </td>

                                        <td>
                                            @php
                                                $statut = $item->post?->statut ?? '—';
                                            @endphp
                                            @php
                                                $etatColors = [
                                                    'validation'                => 'secondary',
                                                    'vente'                     => 'primary',
                                                    'vendu'                     => 'dark',
                                                    'livraison'                 => 'info',
                                                    'livré'                     => 'success',
                                                    'refusé'                    => 'danger',
                                                    'préparation'               => 'warning',
                                                    'en voyage'                 => 'info',
                                                    'en cours de livraison'     => 'info',
                                                    'ramassée'                  => 'info',
                                                    'retourné'                  => 'danger',
                                                    'commande confirmée'       => 'primary',
                                                    'tentative de livraison'   => 'warning',
                                                    'retourné à l\'expéditeur' => 'danger',
                                                    'annulé'                    => 'secondary',
                                                    'livraison retardée'       => 'warning',
                                                    'ramassage planifié'       => 'info',
                                                    'reprogrammé'               => 'primary',
                                                ];
                                                $etatColor = $etatColors[$statut] ?? 'light text-dark';
                                            @endphp

                                            @if(!$item->shipment_id)
                                                {{-- Not synced with Aramex: keep post statut + edit button --}}
                                                <div class="d-flex align-items-center gap-1">
                                                    <span class="badge bg-{{ $etatColor }}">
                                                        {{ $statut }}
                                                    </span>
                                                    <button type="button"
                                                        class="btn btn-sm btn-light p-0 border-0 ms-1 edit-statut-btn"
                                                        data-id="{{ $item->id }}"
                                                        data-type="post"
                                                        data-current="{{ $statut }}">
                                                        <i class="fa fa-pen text-secondary"
                                                            style="font-size:12px;"></i>
                                                    </button>
                                                </div>
                                            @else
                                                {{-- Synced with Aramex: no edit button, show last (dernier) history status --}}
                                                <div class="d-flex align-items-center gap-1 livraison-statut-wrapper"
                                                    data-shipment-id="{{ $item->shipment_id }}">
                                                    <span class="badge bg-secondary livraison-statut-badge">
                                                        <i class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                            @endif
                                        </td>
                                        {{-- <td>
                                            @php
                                                $statut = $item->post?->statut ?? '—';
                                            @endphp

                                            @php
                                                $etatColors = [
                                                    'validation'                => 'secondary',
                                                    'vente'                     => 'primary',
                                                    'vendu'                     => 'dark',
                                                    'livraison'                 => 'info',
                                                    'livré'                     => 'success',
                                                    'refusé'                    => 'danger',
                                                    'préparation'               => 'warning',
                                                    'en voyage'                 => 'info',
                                                    'en cours de livraison'     => 'info',
                                                    'ramassée'                  => 'info',
                                                    'retourné'                  => 'danger',
                                                    'commande confirmée'       => 'primary',
                                                    'tentative de livraison'   => 'warning',
                                                    'retourné à l\'expéditeur' => 'danger',
                                                    'annulé'                    => 'secondary',
                                                    'livraison retardée'       => 'warning',
                                                    'ramassage planifié'       => 'info',
                                                    'reprogrammé'               => 'primary', // bootstrap has no 'purple' by default, see note below
                                                ];
                                                $etatColor = $etatColors[$statut] ?? 'light text-dark';
                                            @endphp
                                            <div class="d-flex align-items-center gap-1">

                                               <span class="badge bg-{{ $etatColor }}">
                                                    {{ $statut }}
                                                </span>
                                            </div>
                                        </td> --}}

                                        <td>
                                            @php
                                                $statut = $item->post?->statut ?? '—';
                                            @endphp
                                            @php
                                                $etatColors = [
                                                    'validation'                => 'secondary',
                                                    'vente'                     => 'primary',
                                                    'vendu'                     => 'dark',
                                                    'livraison'                 => 'info',
                                                    'livré'                     => 'success',
                                                    'refusé'                    => 'danger',
                                                    'préparation'               => 'warning',
                                                    'en voyage'                 => 'info',
                                                    'en cours de livraison'     => 'info',
                                                    'ramassée'                  => 'info',
                                                    'retourné'                  => 'danger',
                                                    'commande confirmée'       => 'primary',
                                                    'tentative de livraison'   => 'warning',
                                                    'retourné à l\'expéditeur' => 'danger',
                                                    'annulé'                    => 'secondary',
                                                    'livraison retardée'       => 'warning',
                                                    'ramassage planifié'       => 'info',
                                                    'reprogrammé'               => 'primary',
                                                ];
                                                $etatColor = $etatColors[$statut] ?? 'light text-dark';
                                            @endphp

                                            @if(!$item->shipment_id || !$item->post?->latestShipmentHistory)
                                                <div class="d-flex align-items-center gap-1">
                                                <span class="badge bg-{{ $etatColor }}">
                                                        {{ $statut }}
                                                    </span>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-center gap-1">
                                                <span class="badge bg-secondary" title="Dernier état Aramex">
                                                        {{ $item->post->latestShipmentHistory->new_etat }}
                                                    </span>
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '—' }}
                                        </td>

                                        <td class="text-wrap" style="max-width:250px;">
                                            @if($item->note)
                                                {{ Str::limit($item->note,120) }}
                                            @else
                                                Aucune note
                                            @endif
                                        </td>

                                        <td>
                                            @if(!$aramexAlreadyShown)
                                                @if($vendorHasUnsynced)
                                                    <button class="btn btn-sm btn-outline-primary mt-1"
                                                        onclick="synchronizeWithAramex({{ $order->id }}, {{ $vendorId }})">
                                                        Synchroniser avec Aramex
                                                    </button>
                                                @else
                                                    <span class="badge bg-success mt-1">Synchronisé</span>
                                                    @php
                                                        $pickupGuid = $order->items->where('vendor_id', $vendorId)->first()?->pickup_guid;
                                                    @endphp
                                                    @if($pickupGuid)
                                                        <button class="btn btn-sm btn-outline-danger mt-1"
                                                            onclick="cancelPickup({{ $order->id }}, '{{ $pickupGuid }}')">
                                                            <i class="bi bi-x-circle"></i> Annuler pickup
                                                        </button>
                                                    @endif
                                                @endif
                                                @php $shownAramexVendors[] = $vendorId; @endphp
                                            @endif

                                            {{-- <button class="btn btn-sm btn-outline-secondary mt-1"
                                                onclick="openNoteModal(
                                                    {{ $order->id }},
                                                    '{{ addslashes($order->note ?? '') }}'
                                                )">
                                                <i class="bi bi-journal-text"></i>
                                                Note
                                            </button> --}}
                                            <button class="btn btn-sm btn-outline-secondary mt-1"
                                                onclick="openNoteModal(
                                                    {{ $item->id }},
                                                    '{{ addslashes($item->note ?? '') }}'
                                                )">
                                                <i class="bi bi-journal-text"></i>
                                                Note
                                            </button>

                                            {{-- <button class="btn btn-sm btn-outline-info mt-1"
                                                onclick="openHistoryModal({{ $item->id }}, '{{ $item->shipment_id ?? '' }}')">
                                                <i class="bi bi-clock-history"></i>
                                                Historique
                                            </button> --}}
                                            <button class="btn btn-sm btn-outline-info mt-1"
                                                onclick="openHistoryModal({{ $item->id }}, '{{ $item->shipment_id ?? '' }}', '{{ $item->cancelled_shipment_id ?? '' }}')">
                                                <i class="bi bi-clock-history"></i>
                                                Historique
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger mt-1"
                                                onclick="confirmDeleteItem({{ $item->id }})">
                                                <i class="bi bi-trash"></i>
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

                <!-- History Modal -->
                <div class="modal fade" id="historyModal" tabindex="-1">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header border-0 pb-0">
                                <div>
                                    <h5 class="modal-title fw-bold">
                                        <i class="bi bi-clock-history me-2 text-primary"></i>
                                        Historique des statuts
                                    </h5>
                                    <small class="text-muted">Expédition : <span id="history-shipment-id" class="fw-semibold text-dark"></span></small>
                                </div>
                                <button type="button" class="btn-close" onclick="historyModalInstance && historyModalInstance.hide()"></button>
                            </div>

                            <div class="modal-body pt-3">

                                <div id="history-loading" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <p class="mt-2 text-muted">Chargement...</p>
                                </div>

                                <div id="history-content" class="d-none">
                                    <div id="history-timeline" class="timeline-container"></div>
                                </div>

                                <div id="history-empty" class="d-none text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Aucun historique disponible.
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
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

// function fetchAndReplace(urlWithParams) {
//     fetch(urlWithParams, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
//     .then(res => res.text())
//     .then(html => {
//         const parser = new DOMParser();
//         const doc = parser.parseFromString(html, 'text/html');

//         const newTbody = doc.querySelector('#commande-table-body');
//         const currentTbody = document.querySelector('#commande-table-body');
//         if (newTbody && currentTbody) {
//             currentTbody.innerHTML = newTbody.innerHTML;
//         }

//         const newPagination = doc.querySelector('.pagination');
//         const currentPagination = document.querySelector('.pagination');
//         if (newPagination && currentPagination) {
//             currentPagination.innerHTML = newPagination.innerHTML;
//             attachPaginationListeners();
//         }
//     });
// }
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
            loadLivraisonStatuses(currentTbody); // <-- added
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
            const baseUrl = "{{ route('orders') }}";
            fetchAndReplace(`${baseUrl}?${query}&page=${page}`);
        });
    });
}

attachPaginationListeners();
</script>
<script>
function synchronizeWithAramex(commandeId, vendorId) {
        console.log('🔍 synchronizeWithAramex called with:', { commandeId, vendorId });

    Swal.fire({
        title: "Synchroniser avec Aramex ?",
        text: "Cette action enverra les informations de ce vendeur à Aramex.",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Oui, synchroniser",
        cancelButtonText: "Annuler",
        confirmButtonColor: "#008080",
        cancelButtonColor: "#d33",
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Synchronisation en cours...",
                text: "Veuillez patienter pendant l'envoi des données à Aramex.",
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading(),
            });

            fetch(`/admin/commande/${commandeId}/sync-aramex`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    "Accept": "application/json"
                },
                body: JSON.stringify({ vendor_id: vendorId })
            })
            .then(res => res.json())
            .then(data => {
                console.log('✅ Response from server:', data);
                if (data.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Synchronisation réussie !",
                        text: data.message,
                        confirmButtonColor: "#008080",
                    }).then(() => location.reload());
                } else {
                    const messages = [...new Set(
                        (data.results || [])
                            .filter(r => !r.success && r.message)
                            .map(r => r.message)
                    )];

                    const messageHtml = messages.length
                        ? messages.map(m => `<p class="mb-1">• ${m}</p>`).join('')
                        : data.message;

                    Swal.fire({
                        icon: "error",
                        title: "Échec de la synchronisation",
                        html: messageHtml,
                        confirmButtonColor: "#d33",
                    });
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    icon: "error",
                    title: "Erreur",
                    text: "Une erreur est survenue lors de la synchronisation.",
                    confirmButtonColor: "#d33",
                });
            });
        }
    });
}

function confirmDeleteOrder(orderId) {
    Swal.fire({
        title: "Êtes-vous sûr de vouloir supprimer cette commande ?",
        text: "Cette action est irréversible !",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui, supprimer",
        cancelButtonText: "Annuler",
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/orders/${orderId}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    "Accept": "application/json"
                }
            }).then(response => response.json())
                .then(data => {
                    Swal.fire("Supprimé!", "La commande a été supprimée.", "success")
                        .then(() => location.reload());
                }).catch(err => {
                    Swal.fire("Erreur", "Une erreur est survenue.", "error");
                });
        }
    });
}

function confirmDeleteItem(itemId) {
    Swal.fire({
        title: "Supprimer cet article de la commande ?",
        text: "Cette action est réversible depuis les commandes supprimées.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui, supprimer",
        cancelButtonText: "Annuler",
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/order-items/${itemId}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire("Supprimé!", "La commande a été supprimée.", "success")
                        .then(() => location.reload());
                } else {
                    Swal.fire("Erreur", "Une erreur est survenue.", "error");
                }
            });
        }
    });
}

function openNoteModal(orderId, currentNote) {
    Swal.fire({
        title: "Ajouter / Modifier la note",
        input: "textarea",
        inputLabel: "Note pour la commande",
        inputValue: currentNote,
        showCancelButton: true,
        confirmButtonText: "Enregistrer",
        cancelButtonText: "Annuler",
        inputPlaceholder: "Saisissez ici votre note...",
        inputAttributes: {
            rows: 6
        }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/orders/${orderId}/note`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ note: result.value })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Note enregistrée",
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    Swal.fire("Erreur", data.message || "Impossible d’enregistrer la note", "error");
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire("Erreur", "Une erreur est survenue", "error");
            });
        }
    });
}

</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.edit-statut-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            const type = btn.dataset.type;
            const current = btn.dataset.current;

            let inputOptions = {};

            if (type === 'post') {
                inputOptions = {
                    'validation': 'Validation',
                    'vente': 'Vente',
                    'vendu': 'Vendu',
                    'livraison': 'Livraison',
                    'livré': 'Livré',
                    'refusé': 'Refusé',
                    'préparation': 'Préparation',
                    'en voyage': 'En voyage',
                    'en cours de livraison': 'En cours de livraison',
                    'ramassée': 'Ramassée',
                    'retourné': 'Retourné',
                    'commande confirmée': 'Commande confirmée',
                    'tentative de livraison': 'Tentative de livraison',
                    'retourné à l\'expéditeur': 'Retourné à l\'expéditeur',
                    'annulé': 'Annulé',
                    'livraison retardée': 'Livraison retardée',
                    'ramassage planifié': 'Ramassage planifié',
                    'reprogrammé': 'Reprogrammé'
                };
            } else if (type === 'order') {
                inputOptions = {
                    'pending': 'Crée',
                    'expédiée': 'Expédiée',
                    'livrée': 'Livrée',
                    'rétablie': 'Rétablie',
                    'annulée': 'Annulée'
                };
            }

            const { value: newValue } = await Swal.fire({
                title: type === 'post' ? 'Modifier le statut du post' : 'Modifier le statut de la commande',
                input: 'select',
                inputOptions: inputOptions,
                inputValue: current,
                showCancelButton: true,
                confirmButtonText: 'Enregistrer',
                cancelButtonText: 'Annuler',
                inputPlaceholder: 'Choisir un statut'
            });

            if (newValue && newValue !== current) {
                fetch(`/admin/update-status/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ type, value: newValue })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Succès', 'Statut mis à jour !', 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Erreur', data.message || 'Une erreur est survenue', 'error');
                    }
                });
            }
        });
    });
});
</script>
<script>
const etatConfig = (etat) => {
    const map = {
        // Post-level statuses (existing)
        'validation':                { color: 'secondary', icon: 'bi-hourglass'            },
        'vente':                     { color: 'primary',   icon: 'bi-tag'                  },
        'vendu':                     { color: 'dark',      icon: 'bi-bag-check'            },
        'livraison':                 { color: 'info',      icon: 'bi-truck'                },
        'livré':                     { color: 'success',   icon: 'bi-check-circle-fill'    },
        'refusé':                    { color: 'danger',    icon: 'bi-x-circle-fill'        },
        'préparation':               { color: 'warning',   icon: 'bi-box-seam'             },
        'en voyage':                 { color: 'info',      icon: 'bi-airplane'             },
        'en cours de livraison':     { color: 'info',      icon: 'bi-truck'                },
        'ramassée':                  { color: 'info',      icon: 'bi-archive'              },
        'retourné':                  { color: 'danger',    icon: 'bi-arrow-return-left'    },
        'supprimée':                 { color: 'danger',    icon: 'bi-trash'                },

        // Aramex item-level statuses (mapAramexToItemStatus)
        'commande confirmée':        { color: 'primary',   icon: 'bi-clipboard-check'      },
        'tentative de livraison':    { color: 'warning',   icon: 'bi-exclamation-triangle'  },
        'retourné à l\'expéditeur':  { color: 'danger',    icon: 'bi-arrow-return-left'    },
        'annulé':                    { color: 'danger',    icon: 'bi-x-circle-fill'        },
        'livraison retardée':        { color: 'warning',   icon: 'bi-clock-history'        },
        'ramassage planifié':        { color: 'info',      icon: 'bi-calendar-event'       },
        'reprogrammé':               { color: 'warning',   icon: 'bi-arrow-repeat'         },

        // Order-level statuses (mapAramexToOrderStatus / orders.status)
        'expédiée':                  { color: 'info',      icon: 'bi-truck'                },
        'livrée':                    { color: 'success',   icon: 'bi-check-circle-fill'    },
        'annulée':                   { color: 'danger',    icon: 'bi-x-circle-fill'        },
        'rétablie':                  { color: 'success',   icon: 'bi-arrow-counterclockwise' },
        'pending':                   { color: 'secondary', icon: 'bi-hourglass'            },
    };
    return map[etat] ?? { color: 'secondary', icon: 'bi-circle' };
};

const etatBadge = (etat) => {
    const { color } = etatConfig(etat);
    return `<span class="badge bg-${color}">${etat ?? '—'}</span>`;
};

let historyModalInstance = null;

function openHistoryModal(itemId, shipmentId, cancelledShipmentId) {
    // document.getElementById('history-shipment-id').textContent = shipmentId || '—';
    document.getElementById('history-shipment-id').textContent = shipmentId || cancelledShipmentId || '—';
    document.getElementById('history-loading').classList.remove('d-none');
    document.getElementById('history-content').classList.add('d-none');
    document.getElementById('history-empty').classList.add('d-none');

    const modalEl = document.getElementById('historyModal');
    if (!historyModalInstance) {
        historyModalInstance = new bootstrap.Modal(modalEl);
    }
    historyModalInstance.show();

    if (!shipmentId && !cancelledShipmentId) {
        document.getElementById('history-loading').classList.add('d-none');
        const emptyEl = document.getElementById('history-empty');
        emptyEl.innerHTML = '<i class="bi bi-inbox fs-1 d-block mb-2"></i>Aucun ID d\'expédition pour cet article.';
        emptyEl.classList.remove('d-none');
        return;
    }

    // fetch(`/admin/shipment/${shipmentId}/history`, {
    //     headers: { 'Accept': 'application/json' }
    // })
    const effectiveId = shipmentId || cancelledShipmentId;
    fetch(`/admin/shipment/${effectiveId}/history`, { headers: { 'Accept': 'application/json' } })
    .then(async res => {
        const data = await res.json();
        if (!res.ok) throw new Error(data.error || 'Erreur inconnue');
        return data;
    })
    .then(data => {
        document.getElementById('history-loading').classList.add('d-none');

        if (!data.length) {
            document.getElementById('history-empty').classList.remove('d-none');
            return;
        }

        const timeline = document.getElementById('history-timeline');
        timeline.innerHTML = data.map((row, i) => {
            const isLast = i === 0; // Aramex returns newest first, adjust if needed

            return `
                <div class="timeline-item">
                    <div class="timeline-dot dot-secondary"></div>
                    <div class="timeline-card">
                        <div class="timeline-arrow">
                            <span class="badge bg-secondary">${row.description ?? '—'}</span>
                            ${isLast ? '<span class="badge bg-light text-muted border ms-1">Dernier</span>' : ''}
                        </div>

                        <div class="timeline-date">
                            <i class="bi bi-calendar3 me-1"></i>${row.date ?? '—'}
                        </div>

                        ${row.location ? `
                            <div class="text-muted small">
                                <i class="bi bi-geo-alt me-1"></i>${row.location}
                            </div>` : ''}

                        ${row.comments ? `
                            <div class="text-muted small mt-1">
                                <i class="bi bi-chat-square-text me-1"></i>${row.comments}
                            </div>` : ''}

                        <div class="d-flex flex-wrap gap-2 mt-2">
                            ${row.code ? `<span class="badge bg-light text-dark border">Code: ${row.code}</span>` : ''}
                            ${row.waybill ? `<span class="badge bg-light text-dark border">Waybill: ${row.waybill}</span>` : ''}
                            ${row.problem_code ? `<span class="badge bg-light text-dark border">Problème: ${row.problem_code}</span>` : ''}
                            ${row.gross_weight ? `<span class="badge bg-light text-dark border">Poids: ${row.gross_weight} ${row.weight_unit ?? ''}</span>` : ''}
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        document.getElementById('history-content').classList.remove('d-none');
    })
    .catch((err) => {
        document.getElementById('history-loading').classList.add('d-none');
        const emptyEl = document.getElementById('history-empty');
        emptyEl.innerHTML = `<i class="bi bi-exclamation-triangle fs-1 d-block mb-2 text-danger"></i>${err.message || 'Erreur lors du chargement.'}`;
        emptyEl.classList.remove('d-none');
    });
}

function loadLivraisonStatuses(scope = document) {
    scope.querySelectorAll('.livraison-statut-wrapper[data-shipment-id]').forEach(wrapper => {
        const shipmentId = wrapper.dataset.shipmentId;
        const badge = wrapper.querySelector('.livraison-statut-badge');
        if (!shipmentId || !badge) return;

        fetch(`/admin/shipment/${shipmentId}/history`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok) throw new Error(data.error || 'Erreur inconnue');
            return data;
        })
        .then(data => {
            badge.innerHTML = '';
            if (!data.length) {
                badge.textContent = '—';
                return;
            }
            // data[0] = "Dernier" statut, same as in the history modal
            badge.textContent = data[0].description ?? '—';
        })
        .catch(() => {
            badge.innerHTML = '';
            badge.textContent = '—';
        });
    });
}

document.addEventListener('DOMContentLoaded', () => loadLivraisonStatuses());

</script>
<script>
    function cancelPickup(orderId, pickupGuid) {
    Swal.fire({
        title: "Annuler le pickup Aramex ?",
        html: `
            <p class="text-muted mb-3">GUID : <code>${pickupGuid}</code></p>
            <label class="form-label text-start d-block">Commentaire (Obligatoire)</label>
            <textarea id="cancel-comments" class="form-control" rows="3"
                placeholder="Raison de l'annulation..."></textarea>
        `,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui, annuler",
        cancelButtonText: "Retour",
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        preConfirm: () => {
            return document.getElementById('cancel-comments').value;
        }
    }).then((result) => {
        if (!result.isConfirmed) return;

        Swal.fire({
            title: "Annulation en cours...",
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading(),
        });

        fetch(`/admin/commande/${orderId}/cancel-pickup`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Accept": "application/json"
            },
            body: JSON.stringify({
                pickup_guid: pickupGuid,
                comments: result.value || ''
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "Pickup annulé !",
                    text: data.message,
                    confirmButtonColor: "#008080",
                }).then(() => location.reload());
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Échec de l'annulation",
                    text: data.message,
                    confirmButtonColor: "#d33",
                });
            }
        })
        .catch(() => {
            Swal.fire("Erreur", "Une erreur est survenue.", "error");
        });
    });
}
</script>
@endsection
