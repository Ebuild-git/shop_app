<div class="container pt-5 pb-5">

    <div class="row">
        <div class="col-sm-3">
            <div class="h4">
                Mes achats
            </div>
            <span class=" text-muted">
                Vous avez actuellement {{ $total }} achats.
            </span>
            <br>
            <br>
            <a href="/mes-publication" class=" link">
                <b>Voir mes publications</b>
            </a>
        </div>
        <div class="col-sm-9">
            <div class="card">
                <div class="p-2">
                    <div class="d-flex justify-content-between">
                        <div class="my-auto">
                            @if ($date)
                                <span class="small text-muted">
                                    Total des achats du {{ $date }} : <b>{{ $achats->count() }}</b>
                                </span>
                            @endif
                        </div>
                        <div>
                            <form wire:submit="filtrer">
                                <div class="input-group mb-3">
                                    <input type="month" class="form-control cusor sm" wire:model="date">
                                    <button type="submit" class="btn p-2 bg-red  ">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                                            wire:loading></span>
                                        Filtrer par date
                                    </button>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
                    <table class="table">
                        @forelse ($achats as $achat)
                            <tr>
                                <td style="width: 41px;">
                                    <div class="avatar-small-product">
                                        <img src="{{ Storage::url($achat->photos[0] ?? '') }}" alt="avtar">
                                    </div>
                                </td>
                                <td>
                                    <a href="/post/{{ $achat->id }}" class="link h6"> {{ $achat->titre }} </a>
                                    <br>
                                    <span class="small text-muted">
                                        <i class="bi bi-calendar3"></i>
                                        Acheter le {{ $achat->sell_at }}
                                    </span>
                                </td>
                                <td>
                                    <span class="link">
                                        <i class="bi bi-tag"></i>
                                        {{ $achat->prix }}
                                        <sup>{{ __('currency') }}</sup>
                                    </span>
                                </td>
                                <td>
                                    <a href="/post/{{ $achat->id }}" class="link h6">
                                        <button class="btn btn-dark btn-sm">
                                            <i class="bi bi-bookmark-check"></i>
                                            Voir
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <div class="alert alert-info text-center">
                                <img width="100" height="100"
                                    src="https://img.icons8.com/pastel-glyph/100/008080/fast-cart.png" alt="fast-cart" />
                                <br>
                                vous n'avez pas d'achat actuellement.
                            </div>
                        @endforelse
                    </table>
            </div>
            <br>

        </div>

    </div>

</div>
