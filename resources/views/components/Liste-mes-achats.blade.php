<div id="table-wrapper" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
    <div id="table-scroll">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" style="width: 51px;"></th>
                    <th scope="col">{{ __('item_name') }}</th>
                    <th scope="col">{{ __('purchase_date') }}</th>
                    <th scope="col">{{ __('purchase_price') }}</th>
                    <th scope="col">{{ __('shopiner') }}</th>
                    <th scope="col" class="text-end">{{ __('expedition_status') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($achats as $achat)
                    @php $post = $achat->post; @endphp
                    <tr>
                        <td style="width: 41px;">
                            <div class="avatar-small-product">
                                <img src="{{ Storage::url($post->photos[0] ?? '') }}" alt="avatar">
                            </div>
                        </td>
                        <td>
                            <a href="/post/{{ $post->id }}" class="link h6">{{ $post->titre }}</a>
                            <div style="font-size: 11px; color: #888; margin-top: 2px;">
                                <span>P{{ $post->id }}</span>
                                &nbsp;·&nbsp;
                                <span>CMD-{{ $achat->order_id }}</span>
                                @if($achat->shipment_id)
                                    &nbsp;·&nbsp;
                                    <span><i class="bi bi-box-seam"></i> {{ $achat->shipment_id }}</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($achat->created_at)->format('d-m-Y') }}
                        </td>
                        <td>
                            @if ($post->changements_prix->count())
                                <div class="d-inline-block text-start">
                                    <span class="strong color d-block">
                                        <i class="bi bi-tag"></i>
                                        {{ $post->getPrix() }} <sup>{{ __('currency') }}</sup>
                                    </span>
                                    <span class="strong text-muted d-block" style="font-size: smaller;">
                                        <strike><i class="bi bi-tag"></i> {{ $post->getOldPrix() }}</strike>
                                        <sup>{{ __('currency') }}</sup>
                                    </span>
                                </div>
                            @else
                                <span class="strong color">
                                    <i class="bi bi-tag"></i>
                                    {{ $post->getPrix() }} <sup>{{ __('currency') }}</sup>
                                </span>
                            @endif
                        </td>
                        <td>
                            @if ($post->user_info)
                                <a href="{{ route('user_profile', ['id' => $post->user_info->id]) }}">
                                    {{ $post->user_info->username }}
                                </a>
                            @endif
                            @if ($post->user_info?->deleted_at)
                                <br/>
                                <span class="text-danger">( {{ __('shopiner supprimé') }} )</span>
                            @endif
                        </td>
                        {{-- <td class="text-end">
                            @php
                                $isCancelled = $achat->trashed() ||
                                            optional($achat->order)->trashed();
                            @endphp

                            @if ($post->user_info?->deleted_at || $isCancelled)
                                <span class="badge bg-danger">{{ __('commande annulée') }}</span>
                            @else
                                <x-StatutLivraison :statut="$post->statut"></x-StatutLivraison>
                            @endif
                        </td> --}}
                        <td class="text-end">
                            @php
                                $isCancelled = $achat->trashed() ||
                                            optional($achat->order)->trashed();
                            @endphp

                            @if ($post->user_info?->deleted_at || $isCancelled)
                                <span class="badge bg-danger">{{ __('commande annulée') }}</span>
                            @elseif ($achat->latestShipmentHistory)
                                <span class="badge bg-info text-dark" title="{{ __('dernier_etat_aramex') }}">
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
                            <div class="alert alert-info text-center">
                                <div>
                                    <img width="100" height="100"
                                        src="https://img.icons8.com/carbon-copy/100/737373/shopping-cart-loaded.png"
                                        alt="shopping-cart-loaded" />
                                </div>
                                <h6 class="text-center">{{ __('no_purchase') }}</h6>
                                <span class="text-muted">
                                    <i>{{ __('no_purchase_message') }}</i>
                                </span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
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
<style>
    .sticky-pagination-container {
    position: sticky;
    bottom: 0;
    background-color: #ffffff;
    padding: 10px 15px;
    border-top: 1px solid #ddd;
    box-shadow: 0 2px 4px rgba(255, 255, 255, 0);
    z-index: 10;
    display: flex;
    justify-content: flex-start;
    align-items: center;
}

/* Responsive Design Adjustment */
@media (max-width: 768px) {
    .sticky-pagination-container-left {
        padding: 8px 10px;
    }
}

</style>
