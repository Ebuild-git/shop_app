<div class="table-responsive text-nowrap ">
    <table class="table" id="sortable-list">
        <thead class="table-dark">
            <tr>
                <td></td>
                <td></td>
                <th>Titre</th>
                <th>sous-catégories</th>
                <th>Frais</th>
                <th></th>
            </tr>
        </thead>

        <tbody class="table-border-bottom-0" wire:sortable-group="updateTaskOrder">
            @forelse ($liste as $item)
                <tr wire:key="{{ $item->id }}" data-id="{{ $item->id }}" class="tb-hover-btn">
                    <td  class="button_drop">
                        <button class=" btn-drag-categorie" type="button">
                            <img width="20" height="20"
                                src="https://img.icons8.com/ios-filled/50/FFFFFF/resize-four-directions.png"
                                alt="resize-four-directions" />
                        </button>
                    </td>
                    <td style="width: 45px !important;">
                        <img src="{{ Storage::url($item->icon) }}" alt="{{ $item->icon }}"
                            style="width: 40px !important">
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
                        Livraison : {{ $item->frais_livraison }} DH <br>
                        Gain : {{ $item->pourcentage_gain }}
                    </td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="/admin/add_sous_categorie/{{ $item->id }}">
                                    <i class="ti ti-eye me-1"></i>
                                    Consulter les sous-catégories
                                </a>
                                <a class="dropdown-item" href="javascript:void(0);"
                                    wire:click='add_luxury({{ $item->id }})'>
                                    <i class="ti ti-star me-1"></i>
                                    Marquer en tant que LUXURY
                                </a>
                                <a class="dropdown-item" data-bs-toggle="modal"
                                    data-bs-target="#modalToggle-{{ $item->id }}" href="javascript:void(0);">
                                    <i class="ti ti-pencil me-1"></i> Modifier
                                </a>
                                <a class="dropdown-item text-danger" href="javascript:void(0)"
                                    wire:confirm="Voulez-vous supprimer ?" wire:click="delete({{ $item->id }})">
                                    <i class="ti ti-trash me-1"></i> Supprimer
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                @include('Admin.categories.modal-update', ['item' => $item])
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
        onEnd: function (event) {
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



    <style>
        .btn-drag-categorie {
            border: none !important;
            background-color: #008080;
            display: none;
        }
        .tb-hover-btn:hover .btn-drag-categorie{
            display: block !important;
        }
    </style>


</div>
