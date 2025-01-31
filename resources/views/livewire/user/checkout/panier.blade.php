<div>
    <h3 class="text-center">
        <b class="color">
            Mon panier
        </b>
    </h3>
    <br>
    <div class="container-fluid bg-light-blue p-4">
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
                            <div class="ms-3 w-100">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="/post/{{ $item['id'] }}/{{ $item['titre'] }}" class="product-title" title="{{ $item['titre'] }}">
                                        <b>{{ Str::limit($item['titre'], 30) }}</b>
                                    </a>
                                    <div class="text-end">
                                        @if ($item['is_solder'])
                                        <span class="text-muted-1">
                                            <strike>{{ $item['old_prix'] }} <sup>DH</sup></strike>
                                        </span>
                                        <span class="price" style="color: #008080;">
                                            {{ $item['prix'] }} <sup>DH</sup>
                                        </span>
                                        @else
                                        <span class="price" style="color: #008080;">
                                            {{ $item['prix'] }} <sup>DH</sup>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div>
                                        <span class="text-muted">Vendeur: {{ $item['vendeur'] }}</span> <br>
                                        <span class="delivery-info">Livraison entre le x et le y</span> <br>
                                        <span class="delivery-fee">
                                            @if (!in_array($item['vendeur'], $processedVendors))
                                            <i class="bi bi-truck" style="color: #008080;"></i>
                                            Frais de Livraison : <b class="frais-font">{{ $item['frais'] ?? 0 }} <sup>DH</sup></b>
                                            @php
                                            $processedVendors[] = $item['vendeur'];
                                            @endphp
                                            @else
                                            <span class="text-muted" style="color: #008080;">Frais de Livraison déjà inclus pour ce vendeur.</span>
                                            <span class="info-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="Les frais de livraison ne sont facturés qu'une seule fois par vendeur.">
                                                <i class="bi bi-info-circle"></i>
                                            </span>
                                            @endif
                                        </span>
                                    </div>
                                    <button class="btn btn-outline-danger btn-sm delete-btn" wire:click="delete({{ $item['id'] }})">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="alert alert-warning text-center">
                        Votre panier est vide !
                    </div>
                    @endforelse
                </div>
                @include('components.alert-livewire')
            </div>
            <div class="col-sm-4 col-12">
                <div class="custom-checkout-card p-3 shadow-sm border-0 rounded-lg">
                    <table class="w-100 table-total-checkout mb-4">
                        <tr>
                            <td class="label">Total des articles</td>
                            <td class="text-end value"><b>{{ count($articles_panier) }}</b></td>
                        </tr>
                        <tr>
                            <td class="label">Sous-total</td>
                            <td class="text-end value"><b>{{ number_format($total, 2, '.', '') }} <sup>DH</sup></b></td>
                        </tr>
                        <tr>
                            <td class="label">Total de frais</td>
                            <td class="text-end value"><b>{{ number_format($totalDeliveryFees, 2, '.', '') }} <sup>DH</sup></b></td>
                        </tr>
                        <tr class="total-row">
                            <td><b class="total-label">TOTAL</b></td>
                            <td class="text-end"><b class="total-value">{{ number_format($totalWithDelivery, 2, '.', '') }} <sup>DH</sup></b></td>
                        </tr>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button class="checkout-btn w-100" @disabled($nbre_article <= 0) wire:click="valider()">
                        <span wire:loading>Validation....</span>
                        <span wire:loading.remove>Valider mon panier</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
