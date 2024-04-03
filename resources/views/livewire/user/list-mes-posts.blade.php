<div class=" p-3">
    <div>
        <div class="d-flex justify-content-between">
            <div>
                <h5>
                    {{ $titre }}
                </h5>
            </div>
            <div>
                <form wire:submit="filtrer">
                    <div class="d-flex justify-content-start mb-3">
                        @if ($filter === true)
                            <select class="form-control sm" wire:model="statut">
                                <option value=""></option>
                                <option value="validation">En validation</option>
                                <option value="vente">En cour de vente</option>
                                <option value="vendu">Vendu</option>
                                <option value="livraison">En cour de Livraison</option>
                                <option value="livré">Déja livré</option>
                            </select>
                        @endif
                        <input type="date" class="form-control sm" wire:model="date">
                        <div class="input-group-append">
                            <button class="btn bg-red p-2" type="submit">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                                    wire:loading></span>
                                <i class="bi bi-binoculars"></i>
                                Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col" style="width: 51px;">#</th>
                <th scope="col">titre</th>
                <th scope="col">Statut</th>
                <th scope="col">Prix</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($posts as $item)
                <tr>
                    <th scope="row">
                        <div class="avatar-small-product">
                            <img src="{{ Storage::url($item->photos[0] ?? '') }}" alt="avtar">
                        </div>
                    </th>
                    <td>
                        <b>
                            <a href="/post/{{ $item->id }}" class="link">{{ $item->titre }}</a>
                        </b> <br>
                        <span class="small">
                            <i>Publié le {{ $item->created_at }}</i>
                        </span>
                    </td>
                    <td class="text-capitalize">
                        {{ $item->statut}}
                    </td>
                    <td>
                       {{ $item->getPrix() }} DH
                    </td>
                    <td style="text-align: right;">
                        @if ($item->propositions->count() > 0)
                            <a href="/publication/{{ $item->id }}/propositions">
                                <button class="btn btn-sm btn-dark">
                                    <i class="bi bi-plug-fill"></i>
                                    Propositions ( {{ $item->propositions->count() }} )
                                </button>
                            </a>
                        @endif
                        @if ($item->sell_at == null && $item->verified_at == null)
                            <a href="/publication/{{ $item->id }}/update">
                                <button class="btn btn-sm btn-info">
                                    <i class="bi bi-pencil-square"></i>
                                    Modifer
                                </button>
                            </a>
                        @endif
                        @if ($item->statut == "validation" ||  $item->statut == "vente")
                            <button class="btn btn-sm bg-red" wire:click="delete({{ $item->id }})"
                                wire:confirm="Voulez-vous supprimer cette publication ?">
                                <i class="bi bi-trash"></i>
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <th colspan="5">
                        <div class="alert alert-warning">
                            Aucun article trouvé pour ces critères de recherche.
                        </div>
                    </th>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
