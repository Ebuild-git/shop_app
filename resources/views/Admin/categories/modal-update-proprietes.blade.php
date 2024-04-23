<!-- Modal 1-->
<div class="modal fade" id="modalToggle-{{ $sous_categorie->id }}-pro" aria-labelledby="modalToggleLabel" tabindex="-1"
    style="display: none" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalToggleLabel">
                    Propriétés de <b>{{ $sous_categorie->titre }}</b>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="alert alert-light">
                    <div class="d-flex p-2 bd-highlight">
                        <div class=" flex-fill bd-highlight p-2 my-auto">
                            <img width="20" height="20"
                                src="https://img.icons8.com/parakeet-line/48/1A1A1A/finger-and-thumb.png"
                                alt="finger-and-thumb" />
                        </div>
                        <div class=" flex-fill bd-highlight text-muted small">
                            Vous avez la possibilité de <b>changer l'ordre</b> <br> des propriétés dans cette
                            catégorie,.

                        </div>
                    </div>
                </div>

                <div class="row" id="sortable-list{{ $sous_categorie->id }}">
                    @forelse ($sous_categorie->proprietes ?? [] as $item)
                        @php
                            $propriete = DB::table('proprietes')->find($item);
                        @endphp
                        @if ($propriete)
                            <div class="col-sm-3 col-3 drop cusor" wire:key="{{ $item }}"
                                data-id="{{ $item }}">
                                <div class="p-2 card mx-autu my-auto text-center">
                                    {{ $propriete->nom }}
                                </div>
                            </div>
                        @endif
                    @empty
                    @endforelse
                </div>

            </div>
        </div>
    </div>

    <script>
        new Sortable(document.getElementById("sortable-list{{ $sous_categorie->id }}"), {
            animation: 150,
            onEnd: function(event) {
                let data = Array.from(event.to.children).map((item, index) => {
                    return item.getAttribute('data-id');
                }).join(',');
                let idsArray = data.split(',');

                fetch('/admin/changer_ordre_propriete_in_sous_categorie?ids=' + idsArray.join(',')+'&id_sous_cat='+{{ $sous_categorie->id }}, {
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
