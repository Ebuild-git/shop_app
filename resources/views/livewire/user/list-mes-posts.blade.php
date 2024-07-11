<div class=" p-3">
    <div>
        <div class="row">
            <div class="col-sm-4">
                <h5>
                    {{ $titre }}
                </h5>
            </div>
            <div class="col-sm-8">
                <form wire:submit="filtrer">
                    <div class="d-flex justify-content-start mb-3">
                        @if ($filter === true)
                            <select class="form-control sm  cusor" wire:model="statut">
                                <option value=""></option>
                                <option value="validation">En validation</option>
                                <option value="vente">En cour de vente</option>
                                <option value="vendu">Vendu</option>
                                <option value="livraison">En cour de Livraison</option>
                                <option value="livré">Déja livré</option>
                            </select>
                        @endif
                        <input type="month" class="form-control cusor " id="month-btn" wire:model="date">
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
        <thead style="background-color: #008080;color: white !important;">
            <tr>
                <th scope="col" style="width: 51px;"></th>
                <th scope="col">Article</th>
                <th scope="col">Statut</th>
                <th scope="col">Prix</th>
                <th scope="col">Décision</th>
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
                        <x-AnnonceStatut :statut="$item->statut"></x-AnnonceStatut>
                    </td>
                    <td>
                        @if ($item->old_prix)
                            <b class="text-danger">
                                {{ $item->prix }} DH
                            </b>
                            <br>
                            <strike class="color strong">
                                {{ $item->old_prix }} DH
                            </strike>
                        @else
                            {{ $item->prix }} DH
                        @endif
                    </td>
                    <td>
                        @if ($item->id_motif != null)
                            <span class="text-secondary cusor" onclick="get_posts_motifs({{ $item->id }})">
                                <b>
                                    <i class="bi bi-eye"></i>
                                    Voir les motifs
                                </b>
                            </span>
                        @endif
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
                        @if ($item->sell_at == null)
                            <button class="btn btn-sm btn-info" onclick="Update_post_price({{ $item->id }})">
                                <i class="bi bi-pencil-square"></i>
                                Réduire le prix
                            </button>
                        @endif
                        @if ($item->statut == 'validation' || $item->statut == 'vente')
                            <button class="btn btn-sm bg-red" wire:click="delete({{ $item->id }})"
                                wire:confirm="Voulez-vous supprimer cette publication ?">
                                <i class="bi bi-trash"></i>
                            </button>
                        @endif

                    </td>
                </tr>
            @empty
                <tr>
                    <th colspan="6">
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



    <script>
        $(document).ready(function() {
            $(".month-input").on('click', function() {
                //make click on month-btn
                $('#month-btn').trigger("click");
                alert('dd');
            });
        });
    </script>





</div>
