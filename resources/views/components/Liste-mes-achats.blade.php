<style>
/* ── Table Wrapper ── */
.table-wrap {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,.05);
    margin-bottom: 24px;
    max-height: 600px;
    overflow-y: auto;
}

.table-wrap table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'DM Sans', sans-serif;
}

.table-wrap thead tr {
    background: #0d7c7c;
    position: sticky;
    top: 0;
    z-index: 10;
}

.table-wrap thead th {
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    padding: 10px 8px;
    text-align: left;
    white-space: nowrap;
    border: none;
}

.table-wrap tbody tr {
    border-bottom: 1px solid #f0f1f4;
    transition: background .15s;
}

.table-wrap tbody tr:last-child {
    border-bottom: none;
}

.table-wrap tbody tr:hover {
    background: #fafbfc;
}

.table-wrap tbody td,
.table-wrap tbody th {
    padding: 10px 8px;
    font-size: 12px;
    vertical-align: middle;
    border: none;
    color: #1a1a2e;
}

/* Item cell */
.avatar-small-product {
    width: 45px;
    height: 45px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
    background: #e8eaed;
}

.avatar-small-product img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.item-meta .item-name {
    font-weight: 600;
    font-size: 12px;
    margin-bottom: 1px;
    color: #1a1a2e;
    max-width: 160px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.item-meta .item-id {
    font-weight: 400;
    font-size: 11px;
    margin-bottom: 1px;
    color: #000000;
}

.item-meta a.link {
    color: #1a1a2e;
    text-decoration: none;
}

.item-meta a.link:hover {
    color: #0d7c7c;
}

/* Price */
.price-new {
    color: #27ae60;
    font-weight: 600;
    font-size: 12px;
}

.price-old {
    color: #888;
    font-weight: 600;
    font-size: 12px;
}

/* Date cell */
.date-cell .date {
    font-size: 12px;
    font-weight: 500;
}

/* Status badges */
.s-badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 18px;
    font-size: 10px;
    font-weight: 600;
    margin-bottom: 2px;
    white-space: normal;
    word-break: break-word;
}

.s-validation { background: #fff3cd; color: #856404; }
.s-vente { background: #dbeeff; color: #2980b9; }
.s-vendu { background: #d5f5e3; color: #1a7a45; }
.s-livraison { background: #e8f4fd; color: #1565c0; }
.s-livre { background: #d5f5e3; color: #27ae60; }
.s-refuse { background: #fde8e8; color: #c0392b; }
.s-preparation { background: #dbeeff; color: #2980b9; }
.s-en-voyage { background: #fff3cd; color: #856404; }
.s-en-cours { background: #e8f4fd; color: #1565c0; }
.s-ramassee { background: #e8e8ff; color: #5c35cc; }
.s-retourne { background: #ede8ff; color: #7c3aed; }
.s-deleted { background: #fde8e8; color: #c0392b; }
.s-annule { background: #fde8e8; color: #c0392b; }

.status-sub {
    font-size: 10px;
    color: #aaa;
    white-space: normal;
    word-break: break-word;
}

.dash {
    color: #ccc;
    font-size: 12px;
}

.underlined-link {
    color: #0d7c7c;
    text-decoration: underline;
    font-weight: 500;
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #aaa;
}

.empty-state img {
    margin-bottom: 12px;
    opacity: .6;
    width: 60px !important;
    height: 60px !important;
}

.empty-state p {
    font-size: 13px;
}

/* Pagination */
.modern-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 12px 0 4px;
    font-family: 'DM Sans', sans-serif;
    position: sticky;
    bottom: 0;
    background: #fff;
    z-index: 10;
}

.page-indicator {
    font-size: 12px;
    color: #555;
    font-weight: 500;
}

.page-button {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1.5px solid #e0e3ea;
    background: #fff;
    color: #555;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background .15s, border-color .15s;
    font-size: 12px;
}

.page-button:hover:not(:disabled) {
    background: #0d7c7c;
    border-color: #0d7c7c;
    color: #fff;
}

.page-button:disabled {
    opacity: .35;
    cursor: not-allowed;
}

.page-button i {
    font-size: 10px;
}

.sticky-pagination-container {
    position: sticky;
    bottom: 0;
    background-color: #ffffff;
    z-index: 10;
}

/* Responsive */
@media (max-width: 768px) {
    .table-wrap {
        border-radius: 10px;
        overflow-x: auto;
    }

    .table-wrap table {
        min-width: 640px;
    }

    .table-wrap thead th,
    .table-wrap tbody td,
    .table-wrap tbody th {
        padding: 8px 6px;
        font-size: 11px;
    }

    .avatar-small-product {
        width: 40px;
        height: 40px;
    }
}
</style>

<div class="table-wrap" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
    <table>
        <thead>
            <tr>
                <th></th>
                <th>{{ __('item_name') }}</th>
                <th>{{ __('purchase_date') }}</th>
                <th>{{ __('purchase_price') }}</th>
                <th>{{ __('shopiner') }}</th>
                <th style="text-align:right;">{{ __('expedition_status') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($achats as $achat)
                @php
                    $post = $achat->post;
                    $isCancelled = $achat->trashed() || optional($achat->order)->trashed();
                    $isPickupCancelled = $achat->pickup_cancelled_at && !$achat->shipment_id;
                @endphp
                <tr>
                    <td>
                        <div class="avatar-small-product">
                            <img src="{{ Storage::url($post->photos[0] ?? '') }}" alt="avatar">
                        </div>
                    </td>
                    <td>
                        <div class="item-meta">
                            <div class="item-name" title="{{ $post->titre }}">
                                <a href="/post/{{ $post->id }}" class="link">{{ $post->titre }}</a>
                            </div>
                            <div class="item-id">
                                {{ 'P' . $post->id }} &nbsp;·&nbsp; CMD-{{ $achat->order_id }}
                                @if($achat->shipment_id)
                                    &nbsp;·&nbsp;
                                    <i class="bi bi-box-seam"></i>
                                    <a href="{{ url('/my-orders') }}?shipment_id={{ $achat->shipment_id }}" class="underlined-link">{{ $achat->shipment_id }}</a>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="date-cell">
                            <div class="date">{{ \Carbon\Carbon::parse($achat->created_at)->format('d-m-Y') }}</div>
                        </div>
                    </td>
                    <td>
                        @if ($post->changements_prix->count())
                            <span class="price-new">{{ $post->getPrix() }} <sup>{{ __('currency') }}</sup></span>
                            <br>
                            <span class="price-old" style="text-decoration: line-through; font-size: 11px;">
                                {{ $post->getOldPrix() }} <sup>{{ __('currency') }}</sup>
                            </span>
                        @else
                            <span class="price-new">{{ $post->getPrix() }} <sup>{{ __('currency') }}</sup></span>
                        @endif
                    </td>
                    <td>
                        @if ($post->user_info)
                            <a href="{{ route('user_profile', ['id' => $post->user_info->id]) }}" class="link">
                                {{ $post->user_info->username }}
                            </a>
                        @endif
                        @if ($post->user_info?->deleted_at)
                            <div class="status-sub text-danger">{{ __('shopiner supprimé') }}</div>
                        @endif
                    </td>
                    <td style="text-align:right;">
                        @if ($post->user_info?->deleted_at || $isCancelled)
                            <span class="s-badge s-annule">{{ __('commande annulée') }}</span>
                        @elseif ($isPickupCancelled)
                            <span class="s-badge s-annule" title="{{ __('pickup_annule_tooltip') }}">
                                {{ __('pickup_annule') }}
                            </span>
                        @elseif ($achat->latestShipmentHistory)
                            <span class="s-badge s-livraison" title="{{ __('dernier_etat_aramex') }}">
                                {{ $achat->latestShipmentHistory->new_etat }}
                            </span>
                        @else
                            <x-StatutLivraison :statut="$post->statut"></x-StatutLivraison>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <img src="https://img.icons8.com/carbon-copy/100/737373/shopping-cart-loaded.png" alt="shopping-cart-loaded" />
                            <p><strong>{{ __('no_purchase') }}</strong></p>
                            <p>{{ __('no_purchase_message') }}</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="sticky-pagination-container">
    <div class="modern-pagination">
        <span class="page-indicator">Page {{ $achats->currentPage() }} of {{ $achats->lastPage() }}</span>
        <button class="page-button" {{ $achats->onFirstPage() ? 'disabled' : '' }} onclick="location.href='{{ $achats->previousPageUrl() }}'">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="page-button" {{ !$achats->hasMorePages() ? 'disabled' : '' }} onclick="location.href='{{ $achats->nextPageUrl() }}'">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</div>
