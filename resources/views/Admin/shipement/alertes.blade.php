@extends('Admin.fixe')
@section('titre', 'Alertes')
@section('content')

@section('body')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="col-lg-12 col-xxl-12 mb-4 order-5 order-xxl-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Alertes — Commandes à surveiller</h5>
                        <small class="text-muted">Commandes où l'acheteur ou un vendeur est supprimé ou en mode voyage</small>
                    </div>
                </div>
                <div class="card-body">
                    <form id="filter-form">
                        <div class="row mb-4 align-items-end g-2">
                            <div class="col-auto">
                                <label for="searchFilter" class="form-label">Rechercher</label>
                                <input type="text" name="search" id="searchFilter" class="form-control form-control-sm"
                                    value="{{ request('search') }}"
                                    placeholder="ID commande, ID expédition, nom vendeur/acheteur">
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
                                <input name="date" type="date" value="{{ request('date') }}"
                                    class="form-control form-control-sm" id="dateFilter">
                            </div>

                            <div class="col-auto d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-sm btn-primary">Appliquer</button>
                                <button type="button" id="reset-btn"
                                    class="btn btn-sm btn-outline-secondary">Réinitialiser</button>
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
                                    <th>Alerte</th>
                                    <th>Article</th>
                                    <th>ID Expédition (Aramex)</th>
                                    <th>Frais Livraison</th>
                                    <th>Statut livraison</th>
                                    <th>Statut Client</th>
                                    <th>Date</th>

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

                                            $vendorHasUnsynced = $order->items
                                                ->where('vendor_id', $vendorId)
                                                ->whereNull('shipment_id')
                                                ->isNotEmpty();

                                            // Why this row is on the Alertes page
                                            $vendorDeleted = $item->vendor?->deleted_at ? true : false;
                                            $vendorVoyage  = $item->vendor?->voyage_mode ? true : false;
                                            $buyerDeleted  = $order->buyer?->deleted_at ? true : false;
                                            $buyerVoyage   = $order->buyer?->voyage_mode ? true : false;
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
                                                            <span class="message-style" onclick="OpenModalMessage(
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
                                                            <span class="message-style" onclick="OpenModalMessage(
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
                                                <div class="d-flex flex-column gap-1 align-items-center">
                                                    @if($vendorDeleted)
                                                        <span class="badge bg-danger">🗑️ Vendeur supprimé</span>
                                                    @endif
                                                    @if($buyerDeleted)
                                                        <span class="badge bg-danger">🗑️ Acheteur supprimé</span>
                                                    @endif
                                                    @if($vendorVoyage)
                                                        <span class="badge bg-warning text-dark">🚨 Vendeur en mode voyage</span>
                                                    @endif
                                                    @if($buyerVoyage)
                                                        <span class="badge bg-warning text-dark">🚨 Acheteur en mode voyage</span>
                                                    @endif
                                                    @if(!$vendorDeleted && !$buyerDeleted && !$vendorVoyage && !$buyerVoyage)
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </div>
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
                                            <td>
                                                @if($item->shipment_id)
                                                    {{ $item->shipment_id }}

                                                    @if($item->cancelled_shipment_id)
                                                        <br>
                                                        <span class="text-muted text-decoration-line-through" style="font-size:11px;">
                                                            {{ $item->cancelled_shipment_id }}
                                                        </span>
                                                    @endif
                                                @elseif($item->cancelled_shipment_id)
                                                    <span
                                                        class="text-muted text-decoration-line-through">{{ $item->cancelled_shipment_id }}</span>
                                                    <br><span class="badge bg-danger bg-opacity-75"
                                                        style="font-size:10px;">Annulé</span>
                                                @else
                                                    —
                                                @endif
                                            </td>

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
                                                        'validation' => 'secondary',
                                                        'vente' => 'primary',
                                                        'vendu' => 'dark',
                                                        'livraison' => 'info',
                                                        'livré' => 'success',
                                                        'refusé' => 'danger',
                                                        'préparation' => 'warning',
                                                        'en voyage' => 'info',
                                                        'en cours de livraison' => 'info',
                                                        'ramassée' => 'info',
                                                        'retourné' => 'danger',
                                                        'commande confirmée' => 'primary',
                                                        'tentative de livraison' => 'warning',
                                                        'retourné à l\'expéditeur' => 'danger',
                                                        'annulé' => 'secondary',
                                                        'livraison retardée' => 'warning',
                                                        'ramassage planifié' => 'info',
                                                        'reprogrammé' => 'primary',
                                                    ];
                                                    $etatColor = $etatColors[$statut] ?? 'light text-dark';
                                                @endphp

                                                @if(!$item->shipment_id)
                                                    <div class="d-flex align-items-center gap-1">
                                                        <span class="badge bg-{{ $etatColor }}">
                                                            {{ $statut }}
                                                        </span>
                                                        <button type="button"
                                                            class="btn btn-sm btn-light p-0 border-0 ms-1 edit-statut-btn"
                                                            data-id="{{ $item->id }}" data-type="post" data-current="{{ $statut }}">
                                                            <i class="fa fa-pen text-secondary" style="font-size:12px;"></i>
                                                        </button>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-center gap-1 livraison-statut-wrapper"
                                                        data-shipment-id="{{ $item->shipment_id }}">
                                                        <span class="badge bg-secondary livraison-statut-badge">
                                                            <i class="spinner-border spinner-border-sm" role="status"
                                                                aria-hidden="true"></i>
                                                        </span>
                                                    </div>
                                                @endif
                                            </td>

                                            <td>
                                                @php
                                                    $statut = $item->post?->statut ?? '—';
                                                @endphp
                                                @php
                                                    $etatColors = [
                                                        'validation' => 'secondary',
                                                        'vente' => 'primary',
                                                        'vendu' => 'dark',
                                                        'livraison' => 'info',
                                                        'livré' => 'success',
                                                        'refusé' => 'danger',
                                                        'préparation' => 'warning',
                                                        'en voyage' => 'info',
                                                        'en cours de livraison' => 'info',
                                                        'ramassée' => 'info',
                                                        'retourné' => 'danger',
                                                        'commande confirmée' => 'primary',
                                                        'tentative de livraison' => 'warning',
                                                        'retourné à l\'expéditeur' => 'danger',
                                                        'annulé' => 'secondary',
                                                        'livraison retardée' => 'warning',
                                                        'ramassage planifié' => 'info',
                                                        'reprogrammé' => 'primary',
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

                                                            $vendorShipmentIds = $order->items
                                                                ->where('vendor_id', $vendorId)
                                                                ->whereNotNull('shipment_id')
                                                                ->pluck('shipment_id');

                                                            $pickupAlreadyConfirmed = \App\Models\ShipmentStatusHistory::whereIn('shipment_id', $vendorShipmentIds)
                                                                ->whereIn('update_code', ['SH012', 'SH314', 'SH308', 'SH312'])
                                                                ->exists();
                                                        @endphp
                                                        @if($pickupGuid)
                                                            <button class="btn btn-sm btn-outline-danger mt-1"
                                                                onclick="cancelPickup({{ $order->id }}, '{{ $pickupGuid }}')"
                                                                @if($pickupAlreadyConfirmed) disabled title="Pickup déjà effectué par Aramex"
                                                                @endif>
                                                                <i class="bi bi-x-circle"></i> Annuler pickup
                                                            </button>
                                                        @endif
                                                    @endif
                                                    @php $shownAramexVendors[] = $vendorId; @endphp
                                                @endif

                                                <button class="btn btn-sm btn-outline-secondary mt-1" onclick="openNoteModal({{ $item->id }})">
                                                    <i class="bi bi-journal-text"></i>
                                                    Note
                                                </button>
                                                <button class="btn btn-sm btn-outline-info mt-1"
                                                    onclick="openHistoryModal({{ $item->id }}, '{{ $item->shipment_id ?? '' }}', '{{ $item->cancelled_shipment_id ?? '' }}')">
                                                    <i class="bi bi-clock-history"></i>
                                                    Historique
                                                </button>
                                                @if($item->shipment_id)
                                                    <a href="{{ route('aramex.label.download', $item->shipment_id) }}" target="_blank"
                                                        class="btn btn-sm btn-outline-success mt-1">
                                                        <i class="bi bi-printer"></i>
                                                        Label
                                                    </a>
                                                @endif
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
                                            <div class="p-3">Aucune alerte pour le moment 🎉</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3">{{ $orders->links('pagination::bootstrap-4') }}</div>

                    <!-- Note Modal -->
                    <div class="modal fade" id="noteModal" tabindex="-1">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold">
                                        <i class="bi bi-journal-text me-2 text-primary"></i>
                                        Notes
                                    </h5>
                                    <button type="button" class="btn-close" onclick="noteModalInstance && noteModalInstance.hide()"></button>
                                </div>
                                <div class="modal-body pt-3">
                                    <div id="note-loading" class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status"></div>
                                        <p class="mt-2 text-muted">Chargement...</p>
                                    </div>

                                    <div id="note-content" class="d-none">
                                        <h6 class="fw-semibold">Notes automatiques</h6>
                                        <div id="note-auto-timeline" class="mb-2"></div>
                                        <div id="note-auto-empty" class="text-muted small mb-2 d-none">Aucune note automatique.</div>

                                        <hr>

                                        <h6 class="fw-semibold">Notes manuelles</h6>
                                        <div id="note-manual-list" class="mb-2"></div>
                                        <div id="note-manual-empty" class="text-muted small mb-2 d-none">Aucune note manuelle.</div>

                                        <div class="d-flex gap-2 mt-3">
                                            <textarea id="new-note-content" class="form-control form-control-sm" rows="2" placeholder="Ajouter une note..."></textarea>
                                            <button class="btn btn-sm btn-primary" onclick="addNote()" style="white-space:nowrap;">
                                                <i class="bi bi-plus-lg"></i> Ajouter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                        <small class="text-muted">Expédition : <span id="history-shipment-id"
                                                class="fw-semibold text-dark"></span></small>
                                    </div>
                                    <button type="button" class="btn-close"
                                        onclick="historyModalInstance && historyModalInstance.hide()"></button>
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
                        loadLivraisonStatuses(currentTbody);
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
            const baseUrl = "{{ route('admin.orders.alerts') }}";
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
                    const baseUrl = "{{ route('admin.orders.alerts') }}";
                    fetchAndReplace(`${baseUrl}?${query}&page=${page}`);
                });
            });
        }

        attachPaginationListeners();
    </script>
    <script>
        function synchronizeWithAramex(commandeId, vendorId) {
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

        let noteModalInstance = null;
        let currentNoteItemId = null;

        function openNoteModal(itemId) {
            currentNoteItemId = itemId;
            document.getElementById('note-loading').classList.remove('d-none');
            document.getElementById('note-content').classList.add('d-none');

            const modalEl = document.getElementById('noteModal');
            if (!noteModalInstance) {
                noteModalInstance = new bootstrap.Modal(modalEl);
            }
            noteModalInstance.show();

            loadNotes(itemId);
        }

        function loadNotes(itemId) {
            fetch(`/admin/order-items/${itemId}/notes`, { headers: { 'Accept': 'application/json' } })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('note-loading').classList.add('d-none');
                    document.getElementById('note-content').classList.remove('d-none');
                    renderAutoNotes(data.info_auto);
                    renderManualNotes(data.notes);
                })
                .catch(() => {
                    document.getElementById('note-loading').classList.add('d-none');
                    Swal.fire('Erreur', 'Impossible de charger les notes.', 'error');
                });
        }

        function renderAutoNotes(infoAuto) {
            const container = document.getElementById('note-auto-timeline');
            const emptyEl = document.getElementById('note-auto-empty');
            container.innerHTML = '';

            if (!infoAuto) {
                emptyEl.classList.remove('d-none');
                return;
            }
            emptyEl.classList.add('d-none');

            const lines = infoAuto.split('\n');
            const match = (lines[0] || '').match(/^\[(.*?)\]\s*(.*)$/);
            const date = match ? match[1] : null;
            const firstLine = match ? match[2] : (lines[0] || '');
            const restLines = lines.slice(1);

            container.innerHTML = `
                <div class="d-flex align-items-start gap-2">
                    <span class="d-inline-block rounded-circle mt-1" style="width:8px;height:8px;background-color:#f0a500;flex-shrink:0;"></span>
                    <div style="font-size:13px;line-height:1.5;">
                        ${date ? `<div class="fw-semibold">${date}</div>` : ''}
                        <div>${escapeHtml(firstLine)}</div>
                        ${restLines.map(l => `<div>${escapeHtml(l)}</div>`).join('')}
                    </div>
                </div>
            `;
        }

        function renderManualNotes(notes) {
            const container = document.getElementById('note-manual-list');
            const emptyEl = document.getElementById('note-manual-empty');
            container.innerHTML = '';

            if (!notes.length) {
                emptyEl.classList.remove('d-none');
                return;
            }
            emptyEl.classList.add('d-none');

            container.innerHTML = notes.map(n => `
                <div class="d-flex align-items-start gap-2 border-bottom pb-2 mb-2" id="note-row-${n.id}">
                    <span class="d-inline-block rounded-circle mt-1" style="width:8px;height:8px;background-color:#0d6efd;flex-shrink:0;"></span>
                    <div style="font-size:13px;line-height:1.5;flex:1;">
                        <div class="fw-semibold">${n.created_at}</div>
                        <div class="note-text">${escapeHtml(n.content)}</div>
                    </div>

                </div>
            `).join('');
        }

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str ?? '';
            return div.innerHTML;
        }

        function addNote() {
            const textarea = document.getElementById('new-note-content');
            const content = textarea.value.trim();
            if (!content) return;

            fetch(`/admin/order-items/${currentNoteItemId}/notes`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ content })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        textarea.value = '';
                        loadNotes(currentNoteItemId);
                    } else {
                        Swal.fire('Erreur', "Impossible d'ajouter la note.", 'error');
                    }
                });
        }

        function editNote(noteId) {
            const row = document.getElementById(`note-row-${noteId}`);
            const currentText = row.querySelector('.note-text').textContent;

            Swal.fire({
                title: 'Modifier la note',
                input: 'textarea',
                inputValue: currentText,
                showCancelButton: true,
                confirmButtonText: 'Enregistrer',
                cancelButtonText: 'Annuler',
                inputAttributes: { rows: 4 }
            }).then((result) => {
                if (result.isConfirmed && result.value.trim()) {
                    fetch(`/admin/order-item-notes/${noteId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ content: result.value.trim() })
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                loadNotes(currentNoteItemId);
                            } else {
                                Swal.fire('Erreur', 'Impossible de modifier la note.', 'error');
                            }
                        });
                }
            });
        }

        function deleteNote(noteId) {
            Swal.fire({
                title: 'Supprimer cette note ?',
                text: 'Cette action est irréversible.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/order-item-notes/${noteId}`, {
                        method: 'DELETE',
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                loadNotes(currentNoteItemId);
                            } else {
                                Swal.fire('Erreur', 'Impossible de supprimer la note.', 'error');
                            }
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
        let historyModalInstance = null;

        function openHistoryModal(itemId, shipmentId, cancelledShipmentId) {
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
                        const isLast = i === 0;

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
