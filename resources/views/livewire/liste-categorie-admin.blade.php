<div>
    <div class="alert alert-light">
        <div class="d-flex p-2 bd-highlight">
            <div class=" flex-fill bd-highlight p-2 my-auto">
                <img width="20" height="20"
                    src="https://img.icons8.com/parakeet-line/48/1A1A1A/finger-and-thumb.png" alt="finger-and-thumb" />
            </div>
            <div class=" flex-fill bd-highlight text-muted small">
                Vous avez la possibilité de <b>changer l'ordre</b> des propriétés dans cette liste, ce qui
                entraînera également le changement de l'ordre dans le formulaire de publication et dans les détails
                des publications.

            </div>
        </div>
    </div>


    <div class="table-responsive text-nowrap ">
        <table class="table" id="sortable-list">
            <thead class="table-dark">
                <tr>
                    <th></th>
                    <th>Titre</th>
                    <th>sous-catégories</th>
                    <th>Frais</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>

            <tbody class="table-border-bottom-0" wire:sortable-group="updateTaskOrder">
                @forelse ($liste as $item)
                    <tr wire:key="{{ $item->id }}" data-id="{{ $item->id }}" class="tb-hover-btn cusor">
                        <td style="width: 45px !important;">
                            <div class="card-image-categorie-list-admin">
                                <img src="{{ Storage::url($item->icon) }}" class="img-card-image-categorie-list-admin"
                                    alt="{{ $item->icon }}">
                            </div>
                        </td>
                        <td>
                            <span class="fw-medium text-capitalize">
                                @if ($item->luxury == 1)
                                    <span style="color: #008080;">
                                        <b>[ <i class="ti ti-star me-1"></i> LUXURY ]</b>
                                    </span>
                                @endif
                                {{ $item->titre }} <br>
                                <span class="small text-muted">
                                    <i>Créé le {{ $item->created_at }} </i>
                                </span>
                            </span>
                        </td>
                        <td>
                            {{ $item->getSousCategories->count() }}
                        </td>
                        <td>
                           Profit : {{ $item->pourcentage_gain }} %   
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="/admin/add_sous_categorie/{{ $item->id }}">
                                        <i class="ti ti-eye me-1"></i>
                                        Consulter les sous-catégories
                                    </a>
                                    @if ($item->luxury == 1)
                                        <a class="dropdown-item" href="javascript:void(0);"
                                            wire:click='add_luxury({{ $item->id }})'>
                                            <i class="ti ti-star me-1"></i>
                                            Rétirer en tant que LUXURY
                                        </a>
                                    @else
                                        <a class="dropdown-item" href="javascript:void(0);"
                                            wire:click='add_luxury({{ $item->id }})'>
                                            <i class="ti ti-star me-1"></i>
                                            Ajouter en tant que LUXURY
                                        </a>
                                    @endif
                                    <a class="dropdown-item" href="/admin/update_categorie/{{ $item->id }}">
                                        <i class="ti ti-pencil me-1"></i> Modifier la catégorie
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td style="text-align: right">
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
                @empty
                    <tr>
                        <td colspan="6">
                            Aucune donnée trouvée !
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>










        <script>
            new Sortable(document.getElementById('sortable-list').querySelector('tbody'), {
                animation: 150,
                onEnd: function(event) {
                    let data = Array.from(event.to.children).map((item, index) => {
                        return item.getAttribute('data-id');
                    }).join(',');
                    let idsArray = data.split(',');

                    fetch('/admin/changer_ordre_categorie?ids=' + idsArray.join(','), {
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

            .card-image-categorie-list-admin {
                width: 44px !important;
                height: 44px !important;
                overflow: hidden !important;

            }

            .img-card-image-categorie-list-admin {
                width: 100% !important;
                height: 100% !important;
                object-fit: cover;
                border-radius: 10px;
                border: solid 1px #008080;

            }

            .img-card-image-categorie-list-admin:hover {
                transform: scale(1.1)
            }
        </style>


    </div>

</div>
