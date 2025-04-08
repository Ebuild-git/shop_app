 <!-- Ajax Sourced Server-side -->


 <div class="card">
     <div class="d-flex justify-content-between" style="height: 30px;">
         <h5 class="card-header" style="padding: 11px;">
             Liste des publications
             @if ($deleted)
                 <span class="text-danger">
                     (Supprimés)
                 </span>
             @endif
             <span wire:loading>
                 <x-loading></x-loading>
             </span>
         </h5>
         <a href="{{ route('liste_publications_supprimer') }}" class="btn text-danger">
             <b>
                 <i class="bi bi-trash3"></i>
                 Corbeille ( {{ $trashCount }} )
             </b>
         </a>
     </div>
     <div class="row p-2">
         <div class="col-sm-12 my-auto">
             <form wire:submit="filtre">
                 <div class="input-group mb-3">
                     <input type="text" class="form-control" wire:model="mot_key"
                         placeholder="Titre,Auteur,Description">

                        <select wire:model="status_filter" class="form-control">
                            <option value="">Tous les statuts</option>
                            <option value="validation">En attente de validation</option>
                            <option value="vente">En vente</option>
                            <option value="vendu">Vendu</option>
                            <option value="livraison">En livraison</option>
                            <option value="livré">Livré</option>
                            <option value="refusé">Refusé</option>
                            <option value="préparation">Préparation</option>
                            <option value="en voyage">En voyage</option>
                            <option value="en cours de livraison">En cours de livraison</option>
                            <option value="ramassée">Ramassée</option>
                            <option value="retourné">Retourné</option>
                        </select>
                     <select wire:model ="region_key" class="form-control">
                         <option value="" selected>Toutes les regions</option>
                         @foreach ($regions as $item)
                             <option value="{{ $item->id }}">{{ $item->nom }}</option>
                         @endforeach
                     </select>
                     <select wire:model ="categorie_key" class="form-control">
                         <option value="" selected>Toutes les catégories</option>
                         @foreach ($categories as $item)
                             <option value="{{ $item->id }}">
                                 {{ $item->titre }}
                                 {{ $item->luxury == true ? '(Luxury)' : '' }}
                             </option>
                         @endforeach
                     </select>
                     <select wire:model="signalement" class="form-control">
                         <option value="">Signalements</option>
                         <option value="Asc">Plus signaler au moins</option>
                         <option value="Des">Moins signaler au plus</option>
                     </select>
                     <input type="month" name="date" wire:model="date" class="form-control" id="">
                     <button class="btn btn-primary" type="submit" id="button-addon2">
                         <i class="fa-solid fa-filter"></i> &nbsp;
                         Filtrer
                     </button>
                 </div>
             </form>
         </div>
     </div>
     <div class="card" style="margin-top: -12px;">
        <div class="card-body">
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">

         <table class="table">
             <thead class="table-dark">
                <tr>

                    @if ($deleted)
                        <!-- Headers for deleted publications -->
                        <th>Photos</th>
                        <th>ID</th>
                        <th style="left: 110px;">Titre</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Date de publication</th>
                        <th>Date de suppression</th>
                        <th>Nom d'utilisateur</th>
                        <th>Raison de la suppression</th>
                        <th>État de l'article</th>
                        <th>Adresse</th>
                        <th>Nombre de vues</th>
                        <th>Nombre de favoris</th>
                        <th style="text-align: center;">Alert</th>
                        <th>Actions</th>
                    @else
                        <!-- Headers for active publications -->
                        <th>Photos</th>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Date de publication</th>
                        <th>Statut de la publication</th>
                        <th>Pseudonyme du vendeur</th>
                        <th>Nombre de favoris</th>
                        <th>État de l'article</th>
                        <th>Localisation</th>
                        <th>Date de dernière mise à jour</th>
                        <th>Commandé</th>
                        <th style="text-align: center;">Alert</th>
                        <th>Actions</th>
                    @endif
                </tr>
             </thead>

             <tbody>
                @foreach ($posts as $post)

                <tr>
                    @if ($deleted)

                        @if (!empty($post->photos) && count($post->photos) > 0)
                        @foreach ($post->photos as $key => $image)
                            @if ($key == 0) <!-- Show only the first image -->
                                <td class="image-cell">
                                    <a href="{{ url('/admin/publication/' . $post->id . '/view') }}">
                                        <img src="{{ Storage::url($image) }}" alt="{{ $post->titre }} - Image 1" class="table-image">
                                    </a>
                                </td>
                            @endif
                            @break <!-- Exit after the first iteration -->
                        @endforeach
                        @endif
                        <td>{{ 'P' . $post->id }}</td>
                        <td style="left: 110px;">{{ $post->titre }}</td>
                        <td>{{ $post->sous_categorie_info->titre }}</td>
                        <td>{{ $post->prix }} <sup>{{ __('currency') }}</sup></td>
                        <td>{{ $post->created_at->format('d-m-Y') }}</td>
                        <td>{{ $post->deleted_at ? $post->deleted_at->format('d-m-Y') : '' }}</td>
                        <td>
                            <a href="/admin/client/{{ $post->user_info->id }}/view">
                            {{ $post->user_info->username }}
                            </a>
                        </td>
                        <td>{{ $post->motif_suppression }}</td>
                        <td>{{ $post->etat }}</td>
                        <td>{{ $post->region->nom ?? '' }}, {{ $post->user_info->address }}</td>
                        <td style="text-align: center;">{{ $post->views }}</td>
                        <td style="text-align: center;">{{ $post->favoris->count() }}</td>
                        <td>
                            @php
                                $signalementCount = $post->signalements->count();
                            @endphp

                            <div class="d-flex align-items-center">
                                <!-- Signal icon and count -->
                                @if($signalementCount > 0)
                                    <span class="d-flex align-items-center">
                                        <i class="bi bi-exclamation-triangle-fill" style="color: rgb(182, 19, 19); font-size: 20px;"></i>
                                        <a href="{{ route('liste_signalement_publications', $post->id) }}" style="text-decoration: none; color: rgb(182, 19, 19); font-weight: bold; margin-left: 5px;">
                                            {{ $signalementCount }}
                                        </a>
                                    </span>
                                @else
                                    <span class="d-flex align-items-center">
                                        <i class="bi bi-check-circle" style="color: #008080; font-size: 20px;"></i>
                                        <a href="{{ route('liste_signalement_publications', $post->id) }}" style="text-decoration: none; color: #008080; font-weight: bold; margin-left: 5px;">
                                            0
                                        </a>
                                    </span>
                                @endif

                                <!-- History button -->
                                <button type="button" class="btn btn-sm btn-dark d-flex align-items-center" style="margin-left: 10px;" onclick="window.location.href='{{ route('liste_signalement_publications', $post->id) }}'">
                                    <i class="bi bi-clock-history" style="font-size: 14px;"></i>
                                </button>
                            </div>
                        </td>
                        <td>
                            <button wire:click="restore({{ $post->id }})" class="btn btn-sm btn-success custom-restore-btn">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $post->id }})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>


                    @else
                        @if (!empty($post->photos) && count($post->photos) > 0)
                        @foreach ($post->photos as $key => $image)
                            @if ($key == 0)
                                <td class="image-cell">
                                    <a href="{{ url('/admin/publication/' . $post->id . '/view') }}">
                                        <img src="{{ Storage::url($image) }}" alt="{{ $post->titre }} - Image 1" class="table-image">
                                    </a>
                                </td>
                            @endif
                            @break
                        @endforeach
                        @endif
                        <td>{{ 'P' . $post->id }}</td>
                        <td>{{ $post->titre }}</td>
                        <td>{{ $post->sous_categorie_info->titre }}</td>
                        <td>{{ $post->prix }} <sup>{{ __('currency') }}</sup></td>
                        <td>{{ $post->created_at->format('d-m-Y') }}</td>
                        <td style="text-align: center;">
                            @if($post->verified_at !== null && $post->sell_at === null && $post->user_info->voyage_mode === 1)
                                <span class="badge badge-warning">En voyage</span>
                            @elseif($post->verified_at !== null && $post->sell_at === null)
                                <span class="badge badge-info">En vente</span>
                            @elseif($post->sell_at !== null)
                                <span class="badge badge-success">Vendu</span>
                            @else
                                @switch($post->statut)
                                    @case('validation')
                                        <span class="badge badge-warning">En attente de validation</span>
                                        @break
                                    @case('livraison')
                                        <span class="badge badge-primary">En livraison</span>
                                        @break
                                    @case('livré')
                                        <span class="badge badge-success">Livré</span>
                                        @break
                                    @case('refusé')
                                        <span class="badge badge-danger">Refusé</span>
                                        @break
                                    @case('préparation')
                                        <span class="badge badge-secondary">Préparation</span>
                                        @break
                                    @case('en cours de livraison')
                                        <span class="badge badge-warning">En cours de livraison</span>
                                        @break
                                    @case('ramassée')
                                        <span class="badge badge-secondary">Ramassée</span>
                                        @break
                                    @case('retourné')
                                        <span class="badge badge-danger">Retourné</span>
                                        @break
                                    @default
                                        <span class="badge badge-light">Statut inconnu</span>
                                @endswitch
                            @endif
                        </td>


                        <td>
                            <a href="/admin/client/{{ $post->user_info->id }}/view">
                            {{ $post->user_info->username }}
                            </a>
                        </td>
                        <td style="text-align: center;">{{ $post->favoris->count() }}</td>
                        <td>{{ $post->etat }}</td>
                        <td>{{ $post->region->nom ?? '' }}, {{ $post->user_info->address }}</td>
                        <td style="text-align: center;">{{ $post->updated_at->format('d-m-Y') }}</td>
                        <td style="text-align: center;">{{ $post->sell_at ? 'Oui' : 'Non' }}</td>
                        <td>
                            @php
                                $signalementCount = $post->signalements->count();
                            @endphp

                            <div class="d-flex align-items-center">
                                <!-- Signal icon and count -->
                                @if($signalementCount > 0)
                                    <span class="d-flex align-items-center">
                                        <i class="bi bi-exclamation-triangle-fill" style="color: rgb(182, 19, 19); font-size: 20px;"></i>
                                        <a href="{{ route('liste_signalement_publications', $post->id) }}" style="text-decoration: none; color: rgb(182, 19, 19); font-weight: bold; margin-left: 5px;">
                                            {{ $signalementCount }}
                                        </a>
                                    </span>
                                @else
                                    <span class="d-flex align-items-center">
                                        <i class="bi bi-check-circle" style="color: #008080; font-size: 20px;"></i>
                                        <a href="{{ route('post_signalers') }}" style="text-decoration: none; color: #008080; font-weight: bold; margin-left: 5px;">
                                            0
                                        </a>
                                    </span>
                                @endif

                                <!-- History button -->
                                <button type="button" class="btn btn-sm btn-dark d-flex align-items-center" style="margin-left: 10px;" onclick="window.location.href='{{ route('liste_signalement_publications', $post->id) }}'">
                                    <i class="bi bi-clock-history" style="font-size: 14px;"></i>
                                </button>
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $post->id }}">
                                <i class="bi bi-trash3"></i>
                            </button>
                            <div class="modal fade" id="deleteModal-{{ $post->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel">Supprimer la publication</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Êtes-vous sûr de vouloir supprimer cette publication ?</p>
                                            <div>
                                                <label for="motif_suppression">Raison de la suppression:</label>
                                                <select wire:model="motif_suppression" class="form-control">
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
                        </td>

                    @endif
                </tr>
                @endforeach
            </tbody>

         </table>

        </div>
     </div>

    <script>
        window.addEventListener('closeModal', event => {
            const modalId = event.detail.id;
            const modalElement = document.getElementById(modalId);
            if (modalElement) {
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                }
            }

            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        });
        window.addEventListener('reloadPage', () => {
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        });
    </script>

    <script>
        function confirmDelete(postId) {
            Swal.fire({
                title: 'Êtes-vous sûr?',
                text: "Vous ne pourrez pas revenir en arrière!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#008080',
                cancelButtonColor: '#000',
                confirmButtonText: 'Oui, supprimez-le!',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Call the Livewire method directly
                    @this.delete_definitivement(postId);

                    Swal.fire(
                        'Supprimé!',
                        'La publication a été supprimée définitivement.',
                        'success'
                    )
                }
            });
        }
    </script>




<style>
    .custom-restore-btn {
        background-color: #008080;
        border-color: #008080;
    }

    .custom-restore-btn:hover {
        background-color: #006666; /* Darker shade for hover */
        border-color: #006666;     /* Match border with hover color */
    }
</style>
