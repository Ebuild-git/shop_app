<div>
    <div class="row">
        <div class="col-sm-8">
            <div class="cart-checkout p-2 card">
                <br>
                @forelse ($articles_panier as $item)
                    <div class="card p-1 mb-2">
                        <div class="d-flex align-items-center">
                            <div class="card-img-checkout flex-shrink-0">
                                <img src="{{ Storage::url($item['photo']) }}" alt="..." >
                            </div>
                            <div class="flex-grow-1 ms-3 w-100">
                                <div class="d-flex justify-content-between">
                                    <a href="/post/{{ $item['id'] }}/{{ $item['titre'] }}" class="color h6 strong">
                                        <b>
                                            {{ Str::limit($item['titre'],30) }}
                                        </b>
                                    </a>
                                        <i class="bi bi-trash3 text-danger btn btn-sm cusor" wire:click="delete( {{ $item['id'] }} )"></i>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <span class="text-muted">
                                            Vendeur : {{ $item['vendeur'] }}
                                        </span> <br>
                                        <b>
                                            Livraison entre le x et le y
                                        </b>
                                    </div>
                                    <div class="text-end">
                                        <b class="h6 strong">
                                            <strong class="color">
                                                {{ $item['prix'] }} DH
                                            </strong>
                                            @if ($item['is_solder'])
                                            <br>
                                                <strike class="text-danger small">
                                                    {{ $item['old_prix'] }} DH
                                                </strike>
                                            @endif
                                        </b>
                                    </div>
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
                        Total des articles :
                    </td>
                    <td>
                        {{ count($articles_panier) }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Sous-total</b>
                    </td>
                    <td>
                        {{ $total }} DH
                    </td>
                </tr>
                <tr>
                    <td>
                        <b class="color">TOTAL</b>
                    </td>
                    <td>
                        <b class="color">
                            {{ $total }} DH
                        </b>
                    </td>
                </tr>
               </table>
            </div>


           
            <br>
            <div class="d-flex justify-content-end">
                    <button class="btn bg-red stretched-link hover-black w-100" @disabled($nbre_article <= 0) wire:click="valider()">
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
        .card-img-checkout img{
            height: 100%;
            width: 100%;
            object-fit: cover
        }
        .cart-checkout{
            background-color: #d6d6d67c !important;
            padding-bottom: 15px;
            padding-top: 10px;
        }
        .table-total-checkout tr{
            border-bottom: solid 1px #d6d6d6;
        }
        .table-total-checkout tr td{
            padding-bottom: 10px;
            padding-top: 10px;
        }
    </style>

</div>
