<div class="row">
    <div class="col-sm-8">

        <div class="alert alert-light">
            <div class="d-flex p-2 bd-highlight">
                <div class=" flex-fill bd-highlight p-2 my-auto">
                    <img width="20" height="20"
                        src="https://img.icons8.com/parakeet-line/48/1A1A1A/finger-and-thumb.png"
                        alt="finger-and-thumb" />
                </div>
                <div class=" flex-fill bd-highlight text-muted small">
                    Vous avez la possibilité de <b>changer l'ordre</b> des sous-catégories dans cette liste, ce qui
                    entraînera également le changement de l'ordre dans le formulaire de publication et dans les détails
                    des publications.

                </div>
            </div>
        </div>

        <div class="table-responsive text-nowrap ">
            <table class="table" id="sortable-list">
                <thead class="table-dark">
                    <tr>
                        <th>Titre</th>
                        <th>Articles</th>
                        <th>Propriétés</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody wire:sortable-group="updateTaskOrder">
                    @forelse ($liste as $item)
                        <tr wire:key="{{ $item->id }}" data-id="{{ $item->id }}" class="tb-hover-btn">
                            <td> {{ $item->titre }} </td>
                            <td> {{ $item->getPost->count() }} </td>
                            <th> {{ json_encode(count($item->proprietes)) }} </th>
                            <td style="text-align: right">
                                <button class="btn btn-sm btn-dark" type="button" data-bs-toggle="modal"
                                    data-bs-target="#modalToggle-{{ $item->id }}-pro">
                                    <i class="bi bi-option"></i>
                                </button>
                                <a href="/admin/update_sous_categorie/{{ $item->id }}">
                                    <button class="btn btn-sm btn-info" type="button">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </a>
                                <button class="btn btn-sm btn-danger" type="button"
                                    onclick="toggle_confirmation({{ $item->id }})">
                                    <i class="bi bi-trash3"></i>
                                </button>
                                <button class="btn btn-sm btn-success d-none" id="confirmBtn{{ $item->id }}"
                                    type="button" wire:confirm="Voulez-vous supprimer ?"
                                    wire:click="delete({{ $item->id }})">
                                    <i class="bi bi-check-circle"></i> &nbsp;
                                    confirmer
                                </button>
                            </td>
                        </tr>
                        @include('Admin.categories.modal-update-proprietes', ['sous_categorie' => $item])
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="alert alert-info">
                                    Aucun résultat !
                                </div>
                            </td>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
    <div class="col-sm-4">
        <div class="card pb-4 pt-3 p-2">
            @include('components.alert-livewire')
            <form wire:submit = "save" class="">
                <div>
                    <label class="form-label" for="modalAddCardName">Titre</label>
                    <input type="text" wire:model="titre" class="form-control" required
                        placeholder="Titre de la sous-catégorie" />
                    @error('titre')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <br>
                <div>
                    <label for="" class="mb-2">Propriétés des annonce de cette sous-catégorie </label>

                    <div class="row">
                        @forelse ($proprietes as $propriete)
                            <div class="col-sm-4">
                                <input type="checkbox" class="form-check-input"
                                    wire:model="proprios.{{ $propriete->id }}" value="{{ $propriete->id }}">
                                {{ $propriete->nom }}
                            </div>
                        @empty
                            <p>Aucune propriété trouvée.</p>
                        @endforelse
                    </div>
                    @error('proprios')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <br>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light">
                        <span wire:loading>
                            <x-loading></x-loading>
                        </span>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script>
        function toggle_confirmation(productId) {
            const confirmBtn = document.getElementById('confirmBtn' + productId);
            if (!confirmBtn.classList.contains('d-none')) {
                confirmBtn.classList.add('d-none');
            } else {
                // Masquer tous les autres boutons de confirmation s'ils sont visibles
                document.querySelectorAll('.confirm-btn').forEach(btn => {
                    if (!btn.classList.contains('d-none')) {
                        btn.classList.add('d-none');
                    }
                });
                confirmBtn.classList.remove('d-none');
            }
        }
    </script>

    <script>
        new Sortable(document.getElementById('sortable-list').querySelector('tbody'), {
            animation: 150,
            onEnd: function(event) {
                let data = Array.from(event.to.children).map((item, index) => {
                    return item.getAttribute('data-id');
                }).join(',');
                let idsArray = data.split(',');

                fetch('/admin/changer_ordre_sous_categorie?ids=' + idsArray.join(','), {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                    })
                    .then(response => {
                        console.log('Ordre mis à jour avec succès.');
                    })
                    .catch(error => {
                        console.error('Erreur lors de la mise à jour de l\'ordre : ', error);
                    });
            }
        });
    </script>
</div>
