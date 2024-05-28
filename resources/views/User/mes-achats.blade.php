@extends('User.fixe')
@section('titre', 'Créer une publication')
@section('content')
@section('body')

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
                            <form method="POST" action="{{ route('filtre-mes-achats') }}">
                                @csrf
                                <div class="input-group mb-3">
                                    <input type="month" class="form-control cusor sm" name="date" value="{{ $date ? $date : null }}">
                                    <button type="submit" class="btn p-2 bg-red  ">
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
                                    DH
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
                        <div class=" text-center pb-5 pt-5" style="text-align: center;">
                            <div>
                                <img width="100" height="100"
                                    src="https://img.icons8.com/carbon-copy/100/737373/shopping-cart-loaded.png"
                                    alt="shopping-cart-loaded" />
                            </div>
                            <h6 class="text-center">Aucun Achat !</h6>
                            <span class="text-muted">
                                <i> vous n'avez pas d'achat actuellement.</i>
                            </span>
                        </div>
                    @endforelse
                </table>
            </div>
            <br>
           
        </div>

    </div>

</div>


@endsection
