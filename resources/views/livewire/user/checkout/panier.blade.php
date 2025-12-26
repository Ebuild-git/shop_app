<div>
    <h3 class="text-center">
        <b class="color">
            {{ __('my_cart')}}
        </b>
    </h3>
    <br>
    <div class="container-fluid bg-light-blue p-4" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
        <div class="row">
            <div class="col-sm-8 col-12">
                <div class="cart-checkout p-3 card">
                    @php
                    $processedVendors = [];
                    @endphp
                    @forelse ($articles_panier as $item)
                    <div class="card p-3 mb-3 shadow-sm border-0 rounded-lg">
                        <div class="d-flex align-items-center">
                            <div class="product-image" style="max-width: 80px;">
                                <img src="{{ Storage::url($item['photo']) }}" alt="Product Image">
                            </div>
                            <div class="ms-3 w-100" style="{{ app()->getLocale() == 'ar' ? 'margin-right: 10px;' : '' }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="/post/{{ $item['id'] }}/{{ $item['titre'] }}" class="product-title" title="{{ $item['titre'] }}">
                                        <b>
                                            {{ Str::limit($item['titre'], 30) }}
                                        </b>
                                    </a>

                                    <div class="{{ app()->getLocale() == 'ar' ? 'text-start ar-mobile-style' : 'text-end' }}">
                                        @if ($item['is_solder'])
                                            <span class="text-muted-1 d-block ar-price">
                                                <strike>{{ $item['old_prix'] }} <sup>{{ __('currency') }}</sup></strike>
                                            </span>
                                            <span class="price d-block ar-price" style="color: #008080;">
                                                {{ $item['prix'] }} <sup>{{ __('currency') }}</sup>
                                            </span>
                                        @else
                                            <span class="price d-block ar-price" style="color: #008080;">
                                                {{ $item['prix'] }} <sup>{{ __('currency') }}</sup>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div>
                                        <span class="text-muted" >{{ __('seller')}}: {{ $item['vendeur'] }}</span> <br>
                                        <span class="delivery-fee">
                                            @if (!in_array($item['vendeur'], $processedVendors))
                                            <i class="bi bi-truck" style="color: #008080;"></i>
                                            {{ __('Frais de Livraison')}} : <b class="frais-font">{{ $item['frais'] ?? 0 }} <sup>{{ __('currency') }}</sup></b>
                                            @php
                                            $processedVendors[] = $item['vendeur'];
                                            @endphp
                                            @else
                                            <span class="text-muted" style="color: #008080;">{{ __('shipping_fees_included')}}</span>
                                            <span class="info-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="Les frais de livraison ne sont facturés qu'une seule fois par vendeur.">
                                                <i class="bi bi-info-circle"></i>
                                            </span>
                                            @endif
                                        </span>
                                    </div>

                                    <button
                                        class="btn btn-outline-danger btn-sm delete-btn {{ app()->getLocale() == 'ar' ? 'ar-mobile-btn' : '' }}"
                                        wire:click="delete({{ $item['id'] }})">
                                        <i class="bi bi-trash3 {{ app()->getLocale() == 'ar' ? 'ar-mobile-icon' : '' }}"></i>
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="alert alert-warning text-center">
                        {{ __('empty_cart2') }}
                    </div>
                    @endforelse
                </div>
                @include('components.alert-livewire')

            </div>
            <div class="col-sm-4 col-12" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
                <div class="custom-checkout-card p-3 shadow-sm border-0 rounded-lg">
                    <table class="w-100 table-total-checkout mb-4">
                        <tr>
                            <td class="label">{{ __('total_articles') }}</td>
                            <td class="text-end value"><b>{{ count($articles_panier) }}</b></td>
                        </tr>
                        <tr>
                            <td class="label">{{ __('subtotal') }}</td>
                            <td class="text-end value"><b>{{ number_format($total, 2, '.', '') }} <sup>{{ __('currency') }}</sup></b></td>
                        </tr>
                        <tr>
                            <td class="label">{{ __('total_fees') }}</td>
                            <td class="text-end value"><b>{{ number_format($totalDeliveryFees, 2, '.', '') }} <sup>{{ __('currency') }}</sup></b></td>
                        </tr>
                        <tr class="total-row">
                            <td><b class="total-label">{{ __('total') }}</b></td>
                            <td class="text-end"><b class="total-value">{{ number_format($totalWithDelivery, 2, '.', '') }} <sup>{{ __('currency') }}</sup></b></td>
                        </tr>
                    </table>
                </div>


                <div class="d-flex justify-content-end mt-3">
                    <button class="checkout-btn w-100" @disabled($nbre_article <= 0 || is_null(auth()->user()->region)) wire:click="valider()">
                        <span wire:loading>{{ __('validating') }}</span>
                        <span wire:loading.remove>{{ __('validate_cart') }}</span>
                    </button>
                </div>

                @if (is_null(auth()->user()->region))
                    @php
                        session(['redirect_after_profile' => url()->current()]);
                    @endphp
                    <div class="custom-alert mt-3">
                        <div class="icon">⚠️</div>
                        <div class="message">{!! __('enter_region') !!}</div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
