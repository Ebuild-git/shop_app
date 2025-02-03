<div>
    @if (session()->has('error'))
        <div class="alert alert-danger small">
            {{ session('error') }}
        </div>
        <hr>
    @enderror
    @if (session()->has('success'))
        <div class="alert alert-success small">
            {{ session('success') }}
        </div>
        <hr>
    @enderror

    @if ($post->verified_at )
        <div class="alert alert-light" role="alert">
            <i class="bi bi-check2-square"></i> &nbsp;
            Validé le {{ $post->verified_at }}
        </div>
    @endif

    <div class="btn-group d-flex gap-2" role="group" style="width: 100%">
        @if ($post->verified_at == null)
            <button type="button" class="btn btn-success d-inline-flex align-items-center justify-content-center flex-fill py-2" wire:click="valider()">
                <i class="bi bi-check-circle me-2"></i>
                Accepter
            </button>
        @endif

        @if ($post->sell_at == null)
            <button type="button" class="btn btn-danger d-inline-flex align-items-center justify-content-center flex-fill py-2" data-bs-toggle="modal" data-bs-target="#deleteModal1-{{ $post->id }}">
                <i class="bi bi-x-lg me-2"></i>
                Supprimer
            </button>
        @endif
    </div>
    @if ($post->sell_at != null)
        <div class="alert alert-light">
            <div class="header">
                INFORMATION DE L'ACHETEUR
            </div>
            <div class="p-2">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <div class="d-flex align-items-start">
                            <div class="d-flex align-items-start">
                                <div class="avatar me-2">
                                    <img src="/assets-admin/img/avatars/2.png" alt="Avatar"
                                        class="rounded-circle">
                                </div>
                                <div class="me-2 ms-1">
                                    <h6 class="mb-0">
                                        {{ $post->acheteur->name }}
                                    </h6>
                                    <small class="text-muted">
                                        {{ $post->acheteur->GetPosts->count() }} publications. <br>
                                       <b> Numéro de Téléphone :</b> {{ $post->acheteur->phone_number ?? '/' }} <br>
                                       <b> Adresse :</b> {{ $post->acheteur->address ?? '/' }} <br>
                                    </small>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <button class="btn btn-label-primary btn-icon btn-sm waves-effect"
                                    onclick="document.location.href='/admin/client/{{ $post->acheteur->id }}/view'">
                                    <i class="ti ti-eye ti-xs"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="btn-group btn-block">
                @if ($post->statut != 'livré')
                    <button type="button" class="btn btn-primary btn-block" wire:click=mark_as_livrer()>
                        Marquer Livré !
                    </button>
                @endif
                <button type="button" class="btn btn-dark btn-block" wire:click=remettre()>
                    <i class="bi bi-x-lg"></i>
                    &nbsp;
                    Remettre a la vente
                </button>
            </div>
        </div>
    @endif


    <div class="modal fade" id="deleteModal1-{{ $post->id }}" tabindex="-1" aria-labelledby="deleteModalLabel1-{{ $post->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel1-{{ $post->id }}">Supprimer la publication</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p style="text-align: left;">Êtes-vous sûr de vouloir supprimer cette publication ?</p>
                    <div class="form-group mt-2">
                        <label for="motif_suppression{{ $post->id }}" style="display: block; text-align: left;">Raison de la suppression:</label>
                        <select id="motif_suppression{{ $post->id }}" class="form-control" wire:model="motif_suppression">
                            <option value="" selected>Sélectionnez un motif</option>
                            <option value="Annonce de produits interdits ou illégaux">Annonce de produits interdits ou illégaux</option>
                            <option value="Annonce multiple du même article">Annonce multiple du même article</option>
                            <option value="Autres violations des politiques du site">Autres violations des politiques du site</option>
                            <option value="Contenu inapproprié">Contenu inapproprié</option>
                            <option value="Description trompeuse de l'état de l'article">Description trompeuse de l'état de l'article</option>
                            <option value="Fraude ou activité suspecte">Fraude ou activité suspecte</option>
                            <option value="Information incorrecte sur la taille, la couleur, etc.">Information incorrecte sur la taille, la couleur, etc.</option>
                            <option value="Photos floues ou de mauvaise qualité">Photos floues ou de mauvaise qualité</option>
                            <option value="Prix excessif pour le produit mis en vente">Prix excessif pour le produit mis en vente</option>
                            <option value="Produit contrefait ou non authentique">Produit contrefait ou non authentique</option>
                            <option value="Publicité non autorisée ou spam">Publicité non autorisée ou spam</option>
                            <option value="Violation des droits d'auteur ou de la propriété intellectuelle">Violation des droits d'auteur ou de la propriété intellectuelle</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" wire:click="confirmDelete({{ $post->id }})" class="btn btn-danger">Confirmer la suppression</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('hide-delete-modal', function() {
            const modal = document.getElementById('deleteModal1-{{ $post->id }}');
            if (modal) {
                modal.classList.remove('show');
                modal.style.display = 'none';
                document.body.classList.remove('modal-open');
                document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
            }
        });
    </script>
</div>
