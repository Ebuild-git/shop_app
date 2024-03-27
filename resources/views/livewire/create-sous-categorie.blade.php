<div class="row">
    <div class="col-sm-8">
        <div class="table-responsive text-nowrap ">
            <table class="table">
                <thead class="table-dark">
                    <tr>
                        <th>Titre</th>
                        <th>Articles</th>
                        <th>Propriétés</th>
                        <th></th>
                    </tr>
                </thead>
                @forelse ($liste as $item)
                    <tr>
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
</div>
