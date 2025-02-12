<div class="row">
    @include('components.alert-livewire')
    <div class="col-sm-12">

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
        <div class="card">
            <div class="card-body">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table" id="sortable-list">
                        <thead class="table-dark">
                            <th>Nom</th>
                            <th>Type</th>
                            <th></th>
                            <th>Affichage</th>
                            <th></th>
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
                                    {{-- <td>
                                        <button class="btn btn-sm" type="button" data-bs-toggle="modal"
                                            data-bs-target="#voir-{{ $proriete->id }}" style="z-index: 1200 !important;">
                                            Voir les propriétés ( {{ count($proriete->options ?? []) }} )
                                        </button>
                                        @include('Admin.categories.modal-ordre-attribue',['proriete'=>$proriete])

                                    </td> --}}
                                    <td>
                                        <button class="btn btn-sm" type="button" data-bs-toggle="modal"
                                            data-bs-target="#voir-{{ $proriete->id }}">
                                            Voir les propriétés ({{ count($proriete->options ?? []) }})
                                        </button>
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

                                <div class="modal fade" id="voir-{{ $proriete->id }}" aria-labelledby="modalToggleLabel"
                                    tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalToggleLabel">
                                                    Détails de la propriété {{ $proriete->nom }} .
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-light">
                                                    <div class="d-flex p-2 bd-highlight">
                                                        <div class="flex-fill bd-highlight p-2 my-auto">
                                                            <img width="20" height="20"
                                                                src="https://img.icons8.com/parakeet-line/48/1A1A1A/finger-and-thumb.png"
                                                                alt="finger-and-thumb" />
                                                        </div>
                                                        <div class="flex-fill bd-highlight text-muted small">
                                                            Vous avez la possibilité de <b>changer l'ordre</b> des propriétés
                                                            dans cette liste, <br> ce qui entraînera également le changement
                                                            de l'ordre dans le <br> formulaire de publication et dans les
                                                            détails des publications.
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- <div class="row" id="sortable-list{{ $proriete->id }}">
                                                    @forelse ($proriete->options ?? [] as $item)
                                                        <div class="col-sm-3 col-3 cusor" wire:key="{{ $item }}"
                                                            data-id="{{ $item }}">
                                                            <div class="alert alert-light text-center">
                                                                {{ $item }}
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <div class="col-12">
                                                            <div class="p-2 text-center">
                                                                Aucune propriété !
                                                            </div>
                                                        </div>
                                                    @endforelse
                                                </div> --}}
                                                <div class="row" id="sortable-list-options-{{ $proriete->id }}">
                                                    @forelse ($proriete->options ?? [] as $item)
                                                        <div class="col-sm-3 col-3 cursor" wire:key="{{ $item }}" data-id="{{ $item }}">
                                                            <div class="alert alert-light text-center">
                                                                {{ $item }}
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <div class="col-12">
                                                            <div class="p-2 text-center">
                                                                Aucune propriété !
                                                            </div>
                                                        </div>
                                                    @endforelse
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal -->
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Initialize SortableJS for each modal's option list when opened
            document.querySelectorAll(".modal").forEach((modal) => {
                modal.addEventListener("shown.bs.modal", function () {
                    let propertyId = this.id.replace("voir-", ""); // Extract property ID
                    let sortableElement = document.getElementById('sortable-list-options-' + propertyId);

                    if (sortableElement && !sortableElement.classList.contains('sortable-initialized')) {
                        new Sortable(sortableElement, {
                            animation: 150,
                            onEnd: function (event) {
                                let sortedIds = Array.from(event.to.children).map(item => item.getAttribute('data-id'));

                                fetch('/admin/changer_ordre_attribus', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        propriete_id: propertyId,
                                        sorted_ids: sortedIds
                                    })
                                })
                                .then(response => console.log('Ordre des options mis à jour'))
                                .catch(error => console.error('Erreur lors de la mise à jour de l\'ordre : ', error));
                            }
                        });
                        sortableElement.classList.add('sortable-initialized'); // Prevent duplicate initialization
                    }
                });
            });
        });
    </script> --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".modal").forEach((modal) => {
                modal.addEventListener("shown.bs.modal", function () {
                    let propertyId = this.id.replace("voir-", ""); // Extract property ID
                    let sortableElement = document.getElementById('sortable-list-options-' + propertyId);

                    if (sortableElement && !sortableElement.dataset.sortableInitialized) {
                        new Sortable(sortableElement, {
                            animation: 150,
                            onEnd: function (event) {
                                let sortedIds = Array.from(event.to.children).map(item => item.getAttribute('data-id'));

                                fetch(`/admin/changer_ordre_attribus?propriete_id=${propertyId}&sorted_ids=${sortedIds.join(",")}`)
                                .then(response => response.json())
                                .then(data => console.log('Ordre des options mis à jour'))
                                .catch(error => console.error('Erreur lors de la mise à jour de l\'ordre : ', error));
                                                        }
                        });

                        sortableElement.dataset.sortableInitialized = "true"; // Mark as initialized
                    }
                });
            });
        });
    </script>

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
