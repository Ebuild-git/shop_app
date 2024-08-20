<div>
    <h3 class="text-center">
        <b class="color">
            Mon panier
        </b>
    </h3>
    <br>

    <div class="row">
        <div class="col-sm-8 col-12">
            <div class="cart-checkout p-2 card">
                @php
                $processedVendors = [];
            @endphp
                @forelse ($articles_panier as $item)
                    <div class="card p-1 mb-2">
                        <div class="d-flex align-items-center">
                            <div class="card-img-checkout flex-shrink-0" style="max-width: 80px;">
                                <img src="{{ Storage::url($item['photo']) }}" alt="..." style="max-width: 100%; height: auto;">
                            </div>
                            <div class="flex-grow-1 ms-3 w-100">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="/post/{{ $item['id'] }}/{{ $item['titre'] }}"
                                        class="color h6 strong post-title"
                                        title="{{ $item['titre'] }}">
                                        <b>{{ Str::limit($item['titre'], 30) }}</b>
                                    </a>

                                    <div class="text-end">
                                        @if ($item['is_solder'])
                                            <b class="h6 strong">
                                                <strike style="color: grey; font-size: small; margin-right:10px;">
                                                    <b style="font-size: small;">{{ $item['old_prix'] }} <sup>DH</sup></b>
                                                </strike>
                                                <span style="color: #008080; font-size: small;">
                                                    <b>{{ $item['prix'] }} <sup>DH</sup></b>
                                                </span>
                                            </b>
                                        @else
                                            <b class="h6 strong">
                                                <span style="color: #008080; font-size: small;">
                                                    <b>{{ $item['prix'] }} <sup>DH</sup></b>
                                                </span>
                                            </b>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div>
                                        <span class="text-muted">Vendeur : {{ $item['vendeur'] }}</span> <br>
                                        <b>Livraison entre le x et le y</b> <br>
                                        <span class="color">
                                            @if (!in_array($item['vendeur'], $processedVendors))
                                                <img width="17" height="17" src="https://img.icons8.com/?size=50&id=43714&format=png&color=008080" alt="delivery" />
                                                Frais de Livraison : <b>{{ $frais ?? 0 }} <sup>DH</sup></b>
                                                @php
                                                    $processedVendors[] = $item['vendeur'];
                                                @endphp
                                            @else
                                                <b>Frais de Livraison déjà inclus pour ce vendeur.*</b>
                                                <span class="text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="Les frais de livraison ne sont facturés qu'une seule fois par vendeur.">
                                                    <i class="bi bi-info-circle"></i>
                                                </span>
                                            @endif
                                        </span>
                                    </div>
                                    <i class="bi bi-trash3 text-danger btn btn-sm cursor" style="margin-top: auto;" wire:click="delete({{ $item['id'] }})"></i>
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
            <div class="card p-2">
                <table class="w-100 table-total-checkout">
                    <tr>
                        <td>
                            Total des articles
                        </td>
                        <td class="text-end">
                            <b> {{ count($articles_panier) }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Sous-total
                        </td>
                        <td class="text-end">
                            <b> {{ number_format($total, 2, '.', '') }} <sup>DH</sup> </b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Total de frais
                        </td>
                        <td class="text-end">
                            <b> {{ number_format($totalDeliveryFees, 2, '.', '') }} <sup>DH</sup> </b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b class="color">TOTAL</b>
                        </td>
                        <td class="text-end">
                            <b class="color">
                                {{ number_format($totalWithDelivery, 2, '.', '') }} <sup>DH</sup>
                            </b>
                        </td>
                    </tr>
                </table>
            </div>
            <br>
            <div class="d-flex justify-content-end">
                <button class="btn bg-red stretched-link hover-black w-100" @disabled($nbre_article <= 0) wire:click="valider()">
                    <span wire:loading>Validation....</span>
                    <span wire:loading.remove>Valider mon panier</span>
                </button>
            </div>
        </div>
    </div>



    <style>
        .card-img-checkout {
            width: 80px;
            height: 80px;
            border-radius: 4px;
            overflow: hidden;
            margin-right: 10px;
        }

        .card-img-checkout img {
            height: 100%;
            width: 100%;
            object-fit: cover
        }

        .cart-checkout {
            background-color: #d6d6d67c !important;
            padding-bottom: 15px;
            padding-top: 10px;
        }

        .table-total-checkout tr {
            border-bottom: solid 1px #d6d6d6;
        }

        .table-total-checkout tr td {
            padding-bottom: 10px;
            padding-top: 10px;
        }
        .post-title {
            display: inline-block;
            max-width: 180px; /* Adjust the max-width as per your layout */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: middle;
            position: relative;
            transition: max-width 0.3s ease-in-out, overflow 0.3s ease-in-out; /* Smooth transition */
        }

        .expanded-title {
            max-width: 100% !important; /* Force the text to expand */
            overflow: visible;
            text-overflow: clip;
            white-space: normal; /* Allow wrapping when expanded */
        }

</style>


</div>
