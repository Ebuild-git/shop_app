<div class="row">
    @include('components.alert-livewire')
    <div class="col-sm-8">

        <div class="alert alert-light">
            <div class="d-flex p-2 bd-highlight">
                <div class=" flex-fill bd-highlight p-2 my-auto">
                    <img width="20" height="20"
                        src="https://img.icons8.com/parakeet-line/48/1A1A1A/finger-and-thumb.png"
                        alt="finger-and-thumb" />
                </div>
                <div class=" flex-fill bd-highlight text-muted small">
                    Vous avez la possibilité de <b>changer l'ordre</b> des propriétés dans cette liste, ce qui
                    entraînera également le changement de l'ordre dans le formulaire de publication et dans les détails
                    des publications.

                </div>
            </div>
        </div>

        <div class="table-responsive text-nowrap ">
            <table class="table text-capitalize" id="sortable-list">
                <thead class="table-dark">
                    <th>Nom</th>
                    <th>Type</th>
                    <td></td>
                    <td>affichage</td>
                    <td></td>
                </thead>
                <tbody class="table-border-bottom-0" wire:sortable-group="updateTaskOrder">
                    @forelse ($prorietes as $proriete)
                        <tr class="tb-hover-btn" wire:key="{{ $proriete->id }}" data-id="{{ $proriete->id }}">
                            <td> {{ $proriete->nom }} </td>
                            <td>
                                @if ($proriete->type == 'number')
                                    Nombre
                                @elseif($proriete->type == 'color')
                                    Couleur
                                @else
                                    Texte
                                @endif
                            </td>
                            <td>
                                @forelse ($proriete->options ?? [] as $op)
                                    {{ $op }} ,
                                @empty
                                @endforelse
                            </td>
                            <td>
                                @if ($proriete->type == 'option')
                                    @if ($proriete->affichage == 'case')
                                        Case
                                    @else
                                        Autcomplete
                                    @endif
                                @endif
                            </td>
                            <td style="text-align: right">
                                <a href="/admin/update_propriete/{{ $proriete->id }}">
                                    <button class="btn btn-sm btn-info" type="button">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </a>
                                <button class="btn btn-sm btn-danger" type="button"
                                    onclick="toggle_confirmation({{ $proriete->id }})">
                                    <i class="bi bi-trash3"></i>
                                </button>
                                <button class="btn btn-sm btn-success d-none" id="confirmBtn{{ $proriete->id }}"
                                    type="button" wire:confirm="Voulez-vous supprimer ?"
                                    wire:click="delete({{ $proriete->id }})">
                                    <i class="bi bi-check-circle"></i> &nbsp;
                                    confirmer
                                </button>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-sm-4">
        <h5>
            Nouvelle propriété
        </h5>

        <form wire:submit="create">
            <div class="mb-3">
                <label class="form-label">Titre</label>
                <input type="text" wire:model="nom" class="form-control @error('nom') is-invalid @enderror"
                    required />
                @error('nom')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label class="form-label">Type de la propriété</label>
                        <select wire:model.live="type" class="form-control @error('type') is-invalid @enderror"
                            required>
                            <option value=""></option>
                            <option value="text">Texte</option>
                            <option value="number">nombre</option>
                            <option value="color">Couleur</option>
                            <option value="option">Cases a coché</option>
                        </select>
                        @error('type')
                            <div class="text-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                @if ($typeselected && $typeselected == 'option')
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label">Méthode d'affichage</label>
                            <select wire:model="affichage" class="form-control @error('type') is-invalid @enderror"
                                required>
                                <option value=""></option>
                                <option value="case">Case a coché</option>
                                <option value="input">Auto Autcomplete</option>
                            </select>
                            @error('type')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                @endif

            </div>

            @if ($typeselected && $typeselected == 'option')
                <br>
                @foreach ($optionsCases as $key => $option)
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">
                            <img width="20" height="20" src="https://img.icons8.com/fluency/48/checked-2.png"
                                alt="checked-2" />
                        </span>
                        <input type="text" class="form-control" wire:model="optionsCases.{{ $key }}">
                        <button class="btn btn-sm btn-light" type="button" wire:click="add_option()">
                            <img width="20" height="20" src="https://img.icons8.com/fluency/48/add--v1.png"
                                alt="add--v1" />
                        </button>
                    </div>
                @endforeach
            @endif
            <br>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    <span wire:loading>
                        <x-loading></x-loading>
                    </span>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>



    <script>
        new Sortable(document.getElementById('sortable-list').querySelector('tbody'), {
            animation: 150,
            onEnd: function(event) {
                let data = Array.from(event.to.children).map((item, index) => {
                    return item.getAttribute('data-id');
                }).join(',');
                let idsArray = data.split(',');

                fetch('/admin/changer_ordre_proprietes?ids=' + idsArray.join(','), {
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


    <style>
        .btn-drag-categorie {
            border: none !important;
            background-color: #008080;
            display: none;
        }

        .tb-hover-btn:hover .btn-drag-categorie {
            display: block !important;
        }
    </style>

</div>
