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
    font-size: 13px;
    font-weight: 600;
    padding: 15px 18px;
    text-align: left;
    white-space: nowrap;
    border: none;
}
.table-wrap thead th small {
    display: block;
    font-weight: 400;
    opacity: .8;
    font-size: 11px;
}

.table-wrap tbody tr {
    border-bottom: 1px solid #f0f1f4;
    transition: background .15s;
}
.table-wrap tbody tr:last-child { border-bottom: none; }
.table-wrap tbody tr:hover { background: #fafbfc; }

.table-wrap tbody td,
.table-wrap tbody th {
    padding: 15px 18px;
    font-size: 14px;
    vertical-align: middle;
    border: none;
    color: #1a1a2e;
}

/* Item cell */
.item-cell {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 200px;
}
.avatar-small-product {
    width: 60px; height: 60px;
    border-radius: 10px;
    overflow: hidden;
    flex-shrink: 0;
    background: #e8eaed;
}
.avatar-small-product img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
}
.item-meta .item-name { font-weight: 600; font-size: 14px; margin-bottom: 2px; color: #1a1a2e; }
.item-meta .item-id { font-weight: 400; font-size: 12px; margin-bottom: 2px; color: #000000; }
.item-meta .item-date { font-size: 12px; color: #aaa; margin-top: 2px; }
.item-meta a.link { color: #1a1a2e; text-decoration: none; }
.item-meta a.link:hover { color: #0d7c7c; }

/* Price */
.price-new  { color: #27ae60; font-weight: 600; font-size: 15px; }
.price-old  { color: #888;    font-weight: 600; font-size: 15px; }
.price-dash { color: #ccc; }

/* Date cell */
.date-cell .date { font-size: 14px; font-weight: 500; }
.date-cell .time { font-size: 12px; color: #aaa; margin-top: 2px; }

/* Status badges */
.s-badge {
    display: inline-block;
    padding: 4px 11px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 4px;
}
.s-validation         { background: #fff3cd; color: #856404; }
.s-vente              { background: #dbeeff; color: #2980b9; }
.s-vendu              { background: #d5f5e3; color: #1a7a45; }
.s-livraison          { background: #e8f4fd; color: #1565c0; }
.s-livre              { background: #d5f5e3; color: #27ae60; }
.s-refuse             { background: #fde8e8; color: #c0392b; }
.s-preparation        { background: #dbeeff; color: #2980b9; }
.s-en-voyage          { background: #fff3cd; color: #856404; }
.s-en-cours           { background: #e8f4fd; color: #1565c0; }
.s-ramassee           { background: #e8e8ff; color: #5c35cc; }
.s-retourne           { background: #ede8ff; color: #7c3aed; }
.s-deleted            { background: #fde8e8; color: #c0392b; }
.s-annule             { background: #fde8e8; color: #c0392b; }

.status-sub { font-size: 12px; color: #aaa; }
.reason-text { font-size: 13px; color: #555; font-weight: 500; max-width: 180px; }
.dash { color: #ccc; }

/* Action buttons */
.btn-reduce {
    display: inline-flex; align-items: center; gap: 6px;
    background: #0d7c7c; color: #fff;
    border: none; border-radius: 8px;
    padding: 7px 13px;
    font-family: 'DM Sans', sans-serif;
    font-size: 13px; font-weight: 500;
    cursor: pointer; transition: background .2s;
    white-space: nowrap;
}
.btn-reduce:hover { background: #0a6060; }

.btn-del {
    display: inline-flex; align-items: center;
    background: #fde8e8; color: #c0392b;
    border: none; border-radius: 8px;
    padding: 7px 10px;
    font-size: 14px; cursor: pointer;
    transition: background .2s;
}
.btn-del:hover { background: #f5c6c6; }

/* Empty state */
.empty-state {
    text-align: center;
    padding: 52px 20px;
    color: #aaa;
}
.empty-state img { margin-bottom: 12px; opacity: .6; }
.empty-state p { font-size: 15px; }

/* Pagination */
.modern-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 16px 0 4px;
    font-family: 'DM Sans', sans-serif;
    position: sticky;        /* ← add this */
    bottom: 0;               /* ← add this */
    background: #fff;        /* ← add this (prevents content showing through) */
    z-index: 10;             /* ← add this */
}
.page-indicator { font-size: 14px; color: #555; font-weight: 500; }
.page-button {
    width: 38px; height: 38px;
    border-radius: 10px;
    border: 1.5px solid #e0e3ea;
    background: #fff;
    color: #555;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: background .15s, border-color .15s;
    font-size: 13px;
}
.page-button:hover:not(:disabled) { background: #0d7c7c; border-color: #0d7c7c; color: #fff; }
.page-button:disabled { opacity: .35; cursor: not-allowed; }

/* Responsive */
@media (max-width: 768px) {
    .table-wrap { border-radius: 10px; overflow-x: auto; }
    .table-wrap table { min-width: 640px; }
    .table-wrap thead th,
    .table-wrap tbody td,
    .table-wrap tbody th { padding: 10px 12px; font-size: 12px; }
    .avatar-small-product { width: 46px; height: 46px; }
}
</style>

<div class="table-wrap" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <table>
        <thead>
            <tr>
                <th></th>
                <th>{{ __('article') }}</th>
                @if($showRemainingTimeColumn)
                    {{-- vente columns --}}
                    <th>{{ __('current_price') }} <small>({{ __('you_earn') }})</small></th>
                    <th>{{ __('base_price') }} <small>({{ __('buyer_pays') }})</small></th>
                    <th>{{ __('last_update1') }}</th>
                @else
                    {{-- annonce columns --}}
                    <th>{{ __('discount_price') }}</th>
                    <th>{{ __('base_price') }}</th>
                    <th>{{ __('last_price_update') }}</th>
                    <th>{{ __('remaining_time_to_update') }}</th>
                @endif
                <th>{{ __('ad_status') }}</th>
                <th>{{ __('deletion_reason') }}</th>
                @if(!$showRemainingTimeColumn)
                    <th></th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($posts as $item)
            <tr id="tr-post-{{ $item->id }}">

                {{-- Thumbnail --}}
                <td>
                    <div class="avatar-small-product">
                        <img src="{{ $item->FirstImage() }}" alt="{{ $item->titre }}">
                    </div>
                </td>

                {{-- Article --}}
                <td>
                    <div class="item-meta">
                        <div class="item-name">
                            <a href="/post/{{ $item->id }}" class="link">
                                {{ Str::limit($item->titre, 20) }}
                            </a>
                        </div>
                        <div class="item-id">
                            {{ 'P' . $item->id }}
                        </div>
                        <div class="item-date">
                            {{ __('Publié le') }} {{ $item->created_at->format('d-m-Y') }} {{ __('at') }} {{ $item->created_at->format('H:i') }}
                        </div>
                    </div>
                </td>

                @if($showRemainingTimeColumn)
                    {{-- VENTE: base price from DB (getOldPrix or fallback), retail price (getPrix), last updated_at --}}
                    <td>
                        @php $basePx = $item->getOldPrix() && $item->getOldPrix() > $item->getPrix() ? $item->getOldPrix() : $item->getPrix(); @endphp
                        <span class="price-new">{{ $basePx }} <sup>{{ __('currency') }}</sup></span>
                    </td>
                    <td>
                        <span class="price-new">{{ $item->getPrix() }} <sup>{{ __('currency') }}</sup></span>
                    </td>
                    <td>
                        <div class="date-cell">
                            <div class="date">{{ $item->updated_at->format('d-m-Y') }}</div>
                            <div class="time">{{ $item->updated_at->format('H:i') }}</div>
                        </div>
                    </td>
                @else
                    {{-- ANNONCE: discount price, base price, last price update, remaining time --}}
                    <td>
                        @if ($item->getOldPrix() && $item->getOldPrix() > $item->getPrix())
                            <span class="price-new">{{ $item->getPrix() }} <sup>{{ __('currency') }}</sup></span>
                        @else
                            <span class="dash">—</span>
                        @endif
                    </td>
                    <td>
                        @if ($item->getOldPrix() && $item->getOldPrix() > $item->getPrix())
                            <span class="price-old">{{ $item->getOldPrix() }} <sup>{{ __('currency') }}</sup></span>
                        @else
                            <span class="price-new">{{ $item->getPrix() }} <sup>{{ __('currency') }}</sup></span>
                        @endif
                    </td>
                    <td>
                        @if($item->updated_price_at)
                            <div class="date-cell">
                                <div class="date">{{ \Carbon\Carbon::parse($item->updated_price_at)->format('d-m-Y') }}</div>
                                <div class="time">{{ \Carbon\Carbon::parse($item->updated_price_at)->format('H:i') }}</div>
                            </div>
                        @else
                            <span class="dash">—</span>
                        @endif
                    </td>
                    <td>
                        <span style="font-size:13px; color:#555;">{{ $item->next_time_to_edit_price() }}</span>
                    </td>
                @endif

                {{-- Ad Status --}}
                @php
                    $isUserDeleted  = $item->user_info && $item->user_info->deleted_at;
                    $hasDeletedOrder = $item->hasDeletedOrders();
                @endphp
                <td>
                    @if (!$item->motif_suppression)
                        {{-- Resolve display badge from statut / voyage_mode --}}
                        @php
                            $s = $item->statut;
                            $vm = optional($item->user_info)->voyage_mode;
                            if ($vm && $item->verified_at && !$item->sell_at) $s = 'en voyage';
                        @endphp

                        @php
                            $badgeMap = [
                                'validation'           => ['class' => 's-validation',  'label' => __('validation')],
                                'vente'                => ['class' => 's-vente',        'label' => __('vente')],
                                'vendu'                => ['class' => 's-vendu',        'label' => __('vendu')],
                                'livraison'            => ['class' => 's-livraison',    'label' => __('livraison')],
                                'livré'                => ['class' => 's-livre',        'label' => __('livré')],
                                'refusé'               => ['class' => 's-refuse',       'label' => __('refusé')],
                                'préparation'          => ['class' => 's-preparation',  'label' => __('préparation')],
                                'en voyage'            => ['class' => 's-en-voyage',    'label' => __('en voyage')],
                                'en cours de livraison'=> ['class' => 's-en-cours',     'label' => __('en cours de livraison')],
                                'ramassée'             => ['class' => 's-ramassee',     'label' => __('ramassée')],
                                'retourné'             => ['class' => 's-retourne',     'label' => __('retourné')],
                            ];
                            $badge = $badgeMap[$s] ?? ['class' => 's-vente', 'label' => $s];
                        @endphp

                        <span class="s-badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>

                        @if ($item->sell_at)
                            <div class="status-sub">
                                {{ \Carbon\Carbon::parse($item->sell_at)->format('d-m-Y') }} {{ __('at') }} {{ \Carbon\Carbon::parse($item->sell_at)->format('H:i') }}
                            </div>
                        @elseif($item->verified_at)
                            <div class="status-sub">
                                {{ __('Listé le') }} {{ \Carbon\Carbon::parse($item->verified_at)->format('d-m-Y') }}
                            </div>
                        @endif

                        @if ($isUserDeleted || $hasDeletedOrder)
                            <span class="s-badge s-annule" style="margin-top:4px; display:inline-block;">
                                {{ __('commande annulée') }}
                            </span>
                        @endif
                    @else
                        <span class="s-badge s-deleted">{{ __('deleted_by_shopin') }}</span>
                    @endif
                </td>

                {{-- Deletion reason --}}
                <td>
                    @if ($item->motif_suppression)
                        <div class="reason-text">{{ \App\Traits\TranslateTrait::TranslateText($item->motif_suppression) }}</div>
                    @else
                        <span class="dash">—</span>
                    @endif
                </td>

                {{-- Actions (annonce only) --}}
                @if(!$showRemainingTimeColumn)
                <td style="text-align:right; white-space:nowrap;">
                    @if (!$item->id_user_buy && $item->statut !== 'validation')
                        <button class="btn-reduce" onclick="Update_post_price({{ $item->id }})">
                            <i class="bi bi-graph-down-arrow"></i>
                            {{ __('Réduire le prix') }}
                        </button>
                    @endif
                    @if ($item->statut == 'validation' || $item->statut == 'vente')
                        &nbsp;
                        <button class="btn-del" type="button" onclick="delete_my_post({{ $item->id }})">
                            <i class="bi bi-trash"></i>
                        </button>
                    @endif
                </td>
                @endif

            </tr>
            @empty
            <tr>
                <td colspan="{{ $showRemainingTimeColumn ? 7 : 9 }}">
                    <div class="empty-state">
                        <img width="80" height="80" src="https://img.icons8.com/ios/100/008080/empty-box.png" alt="empty"/>
                        <p>{{ __('no_items_found') }}</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="modern-pagination">
        <span class="page-indicator">{{ __('Page') }} {{ $currentPage }} / {{ $lastPage }}</span>
        <button class="page-button" {{ $currentPage == 1 ? 'disabled' : '' }} onclick="location.href='{{ $previousPageUrl }}'">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="page-button" {{ $currentPage == $lastPage ? 'disabled' : '' }} onclick="location.href='{{ $nextPageUrl }}'">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</div>

{{-- Pagination --}}


<script>
    const deletePostTranslation = {
        title:         @json(__('delete_post_title')),
        text:          @json(__('delete_post_text')),
        confirm:       @json(__('delete_post_confirm')),
        cancel:        @json(__('delete_post_cancel')),
        success_title: @json(__('delete_post_success_title')),
        success_text:  @json(__('delete_post_success_text')),
        error_title:   @json(__('delete_post_error_title')),
        error_text:    @json(__('delete_post_error_text')),
    };
</script>
