<div>
    <h3 class="text-center">
        <b class="color">
            Mon panier
        </b>
    </h3>
    <br>
    <div class="row">
        <div class="col-sm-8">
            <div class="cart-checkout p-2 card">
                <br>
                @php
                    $processedVendors = [];
                @endphp
                @forelse ($articles_panier as $item)
                    <div class="card p-1 mb-2">
                        <div class="d-flex align-items-center">
                            <div class="card-img-checkout flex-shrink-0">
                                <img src="{{ Storage::url($item['photo']) }}" alt="...">
                            </div>
                            <div class="flex-grow-1 ms-3 w-100">
                                <div class="d-flex justify-content-between">
                                    <a href="/post/{{ $item['id'] }}/{{ $item['titre'] }}" class="color h6 strong">
                                        <b>
                                            {{ Str::limit($item['titre'], 30) }}
                                        </b>
                                    </a>
                                    <div class="text-end">
                                        <b class="h6 strong">
                                            @if ($item['is_solder'])
                                                <strike class="color">
                                                    <b>
                                                        {{ $item['old_prix'] }} <sup>DH</sup>
                                                    </b>
                                                </strike>
                                                <br>
                                            @endif
                                            <span
                                                class="@if ($item['is_solder']) text-danger @else color @endif small">
                                                <b>{{ $item['prix'] }} <sup>DH</sup></b>
                                            </span>
                                        </b>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <span class="text-muted">
                                            Vendeur : {{ $item['vendeur'] }}
                                        </span> <br>
                                        <b>
                                            Livraison entre le x et le y
                                        </b> <br>
                                        <span class="color">

                                            @if (!in_array($item['vendeur'], $processedVendors))
                                            <img width="17" height="17"
                                            src="https://img.icons8.com/?size=50&id=43714&format=png&color=008080" alt="delivery" />
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

                                    <i class="bi bi-trash3 text-danger btn btn-sm cusor"
                                        wire:click="delete( {{ $item['id'] }} )"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="4" class="p-2">
                            <div class="alert alert-warning text-center">
                                Votre panier est vide !
                            </div>
                        </td>
                    </tr>
                @endforelse
            </div>
            @include('components.alert-livewire')
        </div>
        <div class="col-sm-4">
            <div class="card p-2">
                <table class="w-100 table-total-checkout">
                    <tr>
                        <td>
                            Total des articles
                        </td>
                        <td class="text-end">
                            {{ count($articles_panier) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Sous-total
                        </td>
                        <td class="text-end">
                            {{ number_format($total, 2, '.', '') }} <sup>DH</sup>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Total de frais
                        </td>
                        <td class="text-end">
                            {{ number_format($totalDeliveryFees, 2, '.', '') }} <sup>DH</sup>
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
                <button class="btn bg-red stretched-link hover-black w-100" @disabled($nbre_article <= 0)
                    wire:click="valider()">
                    <span wire:loading>
                        Validation....
                    </span>

                    <span wire:loading.remove>
                        Valider mon panier
                    </span>

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
    </style>

</div>
