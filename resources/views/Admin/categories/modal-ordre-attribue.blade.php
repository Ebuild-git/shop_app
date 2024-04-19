<div class="modal fade" id="voir-{{ $proriete->id }}" aria-labelledby="modalToggleLabel"
    tabindex="-1" style="display: none" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
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
                        <div class=" flex-fill bd-highlight p-2 my-auto">
                            <img width="20" height="20"
                                src="https://img.icons8.com/parakeet-line/48/1A1A1A/finger-and-thumb.png"
                                alt="finger-and-thumb" />
                        </div>
                        <div class=" flex-fill bd-highlight text-muted small">
                            Vous avez la possibilité de <b>changer l'ordre</b> des propriétés dans cette liste, <br> ce qui
                            entraînera également le changement de l'ordre dans le <br> formulaire de publication et dans les détails
                            des publications.
        
                        </div>
                    </div>
                </div>
                <div class="row" id="sortable-list{{ $proriete->id }}">
                    @forelse ($proriete->options ?? [] as $item)
                        <div class="col-sm-3 col-3" wire:key="{{ $item }}"data-id="{{ $item }}">
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

<script>
    new Sortable(document.getElementById("sortable-list{{ $proriete->id }}"), {
        animation: 150,
        onEnd: function(event) {
            let data = Array.from(event.to.children).map((item, index) => {
                return item.getAttribute('data-id');
            }).join(',');
            let idsArray = data.split(',');

            fetch('/admin/changer_ordre_attribus?ids=' + idsArray.join(',')+'&id_propriete='+{{ $proriete->id }}, {
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