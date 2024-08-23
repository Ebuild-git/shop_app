
<div id="table-wrapper">
    <div id="table-scroll">
        <table class="table">
            <thead class="tb-head">
                <tr>
                    <th scope="col" style="width: 51px;"></th>
                    <th scope="col">Article</th>
                    <th scope="col">Prix aprés réduction</th>
                    <th scope="col">Prix de base</th>
                    <th scope="col">Dernière modification de prix</th>
                    @if($showRemainingTimeColumn)
                    <th scope="col">Temps restant pour une nouvelle modification</th>
                    @endif
                    <th scope="col">Statut de l'annonce</th>
                    <th scope="col">Raison de suppression par SHOPIN</th>
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
                            <i>Publié le :</i>
                            <br>
                            <i>{{ $item->created_at->format('d-m-Y \à H:i') }}</i>
                        </span>


                    </td>
                    <td class="strong" style="color: {{ $item->getOldPrix() && $item->getOldPrix() > $item->getPrix() ? '#008080' : '' }}; font-size: 12px;">
                        @if ($item->getOldPrix() && $item->getOldPrix() > $item->getPrix())
                            {{ $item->getPrix() }} <sup>DH</sup>
                        @endif
                    </td>
                    <td class="strong" style="color: {{ $item->getOldPrix() && $item->getOldPrix() > $item->getPrix() ? '#808080' : '#008080' }}; font-size: 12px;">
                        @if ($item->getOldPrix() && $item->getOldPrix() > $item->getPrix())
                            {{ $item->getOldPrix() }} <sup>DH</sup>
                        @else
                            {{ $item->getPrix() }} <sup>DH</sup>
                        @endif
                    </td>
                    <td>
                        <span class="small">
                            {{ $item->updated_price_at ?
                            \Carbon\Carbon::parse($item->updated_price_at)->format('d-m-Y \à H:m') : '-' }}
                        </span>
                    </td>
                    @if($showRemainingTimeColumn)
                    <td>
                        <span class="small">
                            {{ $item->next_time_to_edit_price() }}
                        </span>
                    </td>
                    @endif
                    <td class="text-capitalize my-auto">
                        @if (!$item->motif_suppression)
                        <x-AnnonceStatut :statut="$item->statut"></x-AnnonceStatut>
                        @if ($item->sell_at)
                        <div class="small">
                            {{ \Carbon\Carbon::parse($item->sell_at)->format('d-m-Y \à H:m') }}
                        </div>
                        @endif
                        @else
                        <span class="badge" style="background-color:#ce0000; ">
                            Supprimée par Shopin
                        </span>
                        @endif
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
                            <button class="btn btn-sm  bg-red" onclick="Update_post_price({{ $item->id }})">
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
                            <img width="100" height="100" src="https://img.icons8.com/ios/100/008080/empty-box.png"
                                alt="empty-box" />
                            <br>
                            Aucun article trouvé pour ces critères de recherche.
                        </div>
                    </th>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="sticky-pagination-container">
    <div class="modern-pagination">
        <span class="page-indicator">Page {{ $currentPage }} of {{ $lastPage }}</span>
        <button class="page-button" {{ $currentPage == 1 ? 'disabled' : '' }} onclick="location.href='{{ $previousPageUrl }}'">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="page-button" {{ $currentPage == $lastPage ? 'disabled' : '' }} onclick="location.href='{{ $nextPageUrl }}'">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</div>

