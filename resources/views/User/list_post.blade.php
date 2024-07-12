@extends('User.fixe')
@section('titre', 'Mes ' . $type . 's')
@section('body')

    <!-- ======================= Top Breadcrubms ======================== -->
    <div class="gray py-3">
        <div class="container">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/">Accueil</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Mes {{ $type }}s
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="p-3 row">
            <div class="col-sm-6 my-auto">
                <b>Total :</b> {{ $posts->count() }}
            </div>
            <div class="col-sm-6">
                <form method="POST" action="{{ route('post.mes-post') }}">
                    <div class="input-group mb-3">
                        @csrf
                        <select class="form-control sm  cusor" name="statut">
                            <option value="">Tous les statuts</option>
                            <option value="validation">En validation</option>
                            <option value="vente">En cour de vente</option>
                            <option value="vendu">Vendu</option>
                            <option value="livraison">En cour de Livraison</option>
                            <option value="livré">Déja livré</option>
                        </select>
                        <input type="text" class="form-control cusor sm" placeholder="Année / Mois"
                        onfocus="(this.type='month')" onblur="(this.type='text')" name="date"
                        value="{{ $date ? $date : null }}">
                        <div class="input-group-append">
                            <button class="btn bg-red p-2" type="submit">
                                <i class="bi bi-binoculars"></i>
                                Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <table class="table">
                <thead class="tb-head">
                    <tr>
                        <th scope="col" style="width: 51px;"></th>
                        <th scope="col">Article</th>
                        <th scope="col">Prix actuel</th>
                        <th scope="col">Prix initial</th>
                        <th scope="col">Dernière modification de prix</th>
                        <th scope="col">Temps restant pour une nouvelle modification</th>
                        <th scope="col">Statut del'annonce</th>
                        <th scope="col">Raison de suppression</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($posts as $item)
                        <tr id="tr-post-{{ $item->id }}">
                            <th scope="row">
                                <div class="avatar-small-product">
                                    <img src="{{ $item->FirstImage() }}" alt="avatar">
                                </div>
                            </th>
                            <td>
                                <b>
                                    <a href="/post/{{ $item->id }}" class="link h6">
                                        {{ Str::limit($item->titre, 20) }}
                                    </a>
                                </b>
                                <br>
                                <span class="small">
                                    <i>Publié le : {{ $item->created_at->format('d-m-Y \à H:m') }}</i>
                                </span>
                            </td>
                            <td class="strong">
                                {{ $item->getPrix() }} DH
                            </td>
                            <td class="strong">
                                {{ $item->changements_prix->count() ? $item->changements_prix->first()->old_price . ' DH' : '-' }}
                            </td>
                            <td>
                                <span class="small">
                                    {{ $item->updated_price_at ? \Carbon\Carbon::parse($item->updated_price_at)->format('d-m-Y \à H:m') : '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="small">
                                    {{ $item->next_time_to_edit_price() }}
                                </span>
                            </td>
                            <td class="text-capitalize">
                                <x-AnnonceStatut :statut="$item->statut"></x-AnnonceStatut>
                            </td>
                            <td>
                                @if ($item->motif_suppression)
                                    {{ $item->motif_suppression }}
                                @else
                                    -
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    @if (!$item->id_user_buy)
                                        <button class="btn btn-sm  bg-red"
                                            onclick="Update_post_price({{ $item->id }})">
                                            <i class="bi bi-graph-down-arrow"></i>
                                            Réduire le prix
                                        </button> &nbsp;
                                    @endif
                                    @if ($item->statut == 'validation' || $item->statut == 'vente')
                                        <button class="btn btn-sm btn-danger" type="button"
                                            onclick="delete_my_post({{ $item->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="9">
                                <div class="alert alert-info text-center">
                                    <img width="100" height="100"
                                        src="https://img.icons8.com/ios/100/008080/empty-box.png" alt="empty-box" />
                                    <br>
                                    Aucun article trouvé pour ces critères de recherche.
                                </div>
                            </th>
                        </tr>
                    @endforelse
                </tbody>
                {{ $posts->links('pagination::bootstrap-4') }}
            </table>

        </div>

    </div>
@endsection

@section('modal')
    <!-- Modal pour modifier le prix -->
    <div class="modal fade" id="Modal-Update-Post-Price" tabindex="1" role="dialog" aria-labelledby="UpdatePrice"
        aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="UpdatePrice">
                <div class="modal-headers">
                    <button type="button" class="close" onclick="close_update_price()">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <div style="text-align: left;">
                        @livewire('User.UpdatePrix')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal pour modifier le prix -->
    <script>
        function close_update_price() {
            //reload page
            location.reload();
        }

  
    </script>

    </script>

    <style>
        .data-input {
            overflow: hidden;
            width: 5px;
            height: 5px;
        }
    </style>
@endsection
