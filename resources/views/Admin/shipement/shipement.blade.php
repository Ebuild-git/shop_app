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
                                <th>État</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th>Note</th>
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
                                            <div class="d-flex align-items-center gap-1">
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

                                                <button type="button"
                                                    class="btn btn-sm btn-light p-0 border-0 ms-1 edit-statut-btn"
                                                    data-id="{{ $item->id }}"
                                                    data-type="post"
                                                    data-current="{{ $statut }}"
                                                    title="Modifier le statut du post">
                                                    <i class="fa fa-pen text-secondary" style="font-size: 12px;"></i>
                                                </button>
                                            </div>
                                        </td>

                                        <td>
                                            @php $status = $order->status ?? '—'; @endphp
                                            <div class="d-flex align-items-center gap-1">
                                                @switch($status)
                                                    @case('pending')
                                                        <span class="badge bg-secondary">Crée</span>
                                                        @break
                                                    @case('expédiée')
                                                        <span class="badge bg-info text-dark">Expédiée</span>
                                                        @break
                                                    @case('livrée')
                                                        <span class="badge bg-success">Livrée</span>
                                                        @break
                                                    @case('Rétablie')
                                                        <span class="badge bg-success">Rétablie</span>
                                                        @break
                                                    @case('annulée')
                                                        <span class="badge bg-danger">Annulée</span>
                                                        @break
                                                    @case('supprimée')
                                                        <span class="badge bg-danger">Supprimée</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-light text-dark">{{ ucfirst($status) }}</span>
                                                @endswitch

                                                <button type="button"
                                                    class="btn btn-sm btn-light p-0 border-0 ms-1 edit-statut-btn"
                                                    data-id="{{ $item->id }}"
                                                    data-type="order"
                                                    data-current="{{ $status }}"
                                                    title="Modifier le statut de la commande">
                                                    <i class="fa fa-pen text-secondary" style="font-size: 12px;"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '—' }}</td>

                                        <td class="text-wrap" style="max-width: 250px;">
                                            @if($order->note)
                                                <span>{{ Str::limit($order->note, 120) }}</span>
                                            @else
                                                <span>Aucune note</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if(!$item->shipment_id)
                                                <button class="btn btn-sm btn-outline-primary mt-1"
                                                    onclick="synchronizeWithAramex({{ $order->id }})">
                                                    Synchroniser avec Aramex
                                                </button>
                                            @else
                                                <span class="badge bg-success mt-1">Synchronisé</span>
                                            @endif
                                            <button class="btn btn-sm btn-outline-secondary mt-1"
                                                onclick="openNoteModal({{ $order->id }}, '{{ addslashes($order->note ?? '') }}')">
                                                <i class="bi bi-journal-text"></i> Note
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger mt-1"
                                                onclick="confirmDeleteOrder({{ $order->id }})">
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
function synchronizeWithAramex(commandeId) {
    Swal.fire({
        title: "Synchroniser avec Aramex ?",
        text: "Cette action enverra les informations de la commande à Aramex.",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Oui, synchroniser",
        cancelButtonText: "Annuler",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Synchronisation en cours...",
                text: "Veuillez patienter pendant l'envoi des données à Aramex.",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            fetch(`/admin/commande/${commandeId}/sync-aramex`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Synchronisation réussie !",
                        text: data.message,
                        confirmButtonColor: "#3085d6",
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Échec de la synchronisation",
                        html: data.message + "<br><br><pre>" + JSON.stringify(data.results, null, 2) + "</pre>",
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
                    'retourné': 'Retourné'
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


@endsection
