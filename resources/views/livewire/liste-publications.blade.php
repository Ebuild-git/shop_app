 <!-- Ajax Sourced Server-side -->


 <div class="card">
     <div class="d-flex justify-content-between">
         <h5 class="card-header">
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
                     <select wire:model ="type" class="form-control">
                         <option value="" selected>Toutes les publications</option>
                         <option value="validation">En cour de validation</option>
                         <option value="vente">En vente</option>
                         <option value="vendu">vendu</option>
                         <option value="livraison">en cour de livraison</option>
                         <option value="livré">livré</option>
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
     <div class="card">
        <div class="card-body">
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">

         <table class="table">
             <thead class="table-dark">
                <tr>
                    <!-- Conditionally display table headers based on deleted status -->
                    @if ($deleted)
                        <!-- Headers for deleted publications -->
                        <th>Photos</th>
                        <th>Titre</th>
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
                        <th>Alert</th>
                        <th>Historique des modifications</th>
                    @else
                        <!-- Headers for active publications -->
                        <th>Photos</th>
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
                        <th>Alert</th>
                    @endif
                </tr>
             </thead>

             <tbody>
                @foreach ($posts as $post)
                <tr>
                    @if ($deleted)
                        <td>
                            <!-- Display the first photo -->

                        </td>
                        <td>{{ $post->titre }}</td>
                        <td>{{ $post->sous_categorie_info->titre }}</td>
                        <td>{{ $post->prix }} <sup>DH</sup></td>
                        <td>{{ $post->created_at->format('d-m-Y') }}</td>
                        <td>{{ $post->deleted_at ? $post->deleted_at->format('d-m-Y') : '' }}</td>
                        <td>{{ $post->user_info->username }}</td>
                        <td>{{ $post->motif_suppression }}</td>
                        <td>{{ $post->etat }}</td>
                        <td>{{ $post->region->nom ?? '' }}, {{ $post->user_info->address }}</td>
                        <td style="text-align: center;">{{ $post->views }}</td>
                        <td style="text-align: center;">{{ $post->favoris->count() }}</td>
                        <td>{{ $post->alert }}</td>
                        <td>
                            <!-- Display the history of changes (if applicable) -->
                            <!-- You might need to fetch this via a separate table or relationship -->
                        </td>

                    @else
                        <td>
                            <!-- Display the first photo -->

                        </td>
                        <td>{{ $post->titre }}</td>
                        <td>{{ $post->sous_categorie_info->titre }}</td>
                        <td>{{ $post->prix }} <sup>DH</sup></td>
                        <td>{{ $post->created_at->format('d-m-Y') }}</td>
                        <td style="text-align: center;">{{ $post->statut }}</td>
                        <td>{{ $post->user_info->username }}</td>
                        <td style="text-align: center;">{{ $post->favoris->count() }}</td>
                        <td>{{ $post->etat }}</td>
                        <td>{{ $post->region->nom ?? '' }}, {{ $post->user_info->address }}</td>
                        <td style="text-align: center;">{{ $post->updated_at->format('d-m-Y') }}</td>
                        <td style="text-align: center;">{{ $post->sell_at ? 'Oui' : 'Non' }}</td>
                        <td>{{ $post->alert }}</td>
                    @endif
                </tr>
                @endforeach
            </tbody>

         </table>

        </div>
     </div>


