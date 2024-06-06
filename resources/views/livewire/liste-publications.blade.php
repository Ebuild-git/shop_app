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

     @include('components.alert-livewire')

     <div class="table-responsive text-nowrap">
         <table class="datatables-ajax table">
             <thead class="{{ $deleted ? 'table-red' : 'table-dark' }}">
                 <tr>
                     <th>ID</th>
                     <th style="width: 30px;"></th>
                     <th>Titre</th>
                     <th>État</th>
                     <th>Auteur</th>
                     <th>Catégorie </th>
                     <th>Likes</th>
                     <th title="Signalements">
                         <i class="bi bi-exclamation-triangle"></i> Alert
                     </th>
                     <th>Prix</th>
                     <th>Localisation</th>
                     <th>Statut</th>
                     <th>
                         mise à jour
                     </th>
                     <td></td>
                 </tr>
             </thead>

             <tbody>
                 @forelse ($posts as $post)
                     <tr>
                         <td>
                             {{ $post->id }}
                         </td>
                         <td>
                             <div class="avatar me-2">
                                 <img src="{{ $post->FirstImage() }}" alt="Avatar" class="rounded">
                             </div>
                         </td>
                         <td>
                            <strong>
                                {{ Str::of($post->titre)->limit(30) }}
                            </strong>
                            <br>
                             <span class="small">
                                 @if ($post->deleted_at)
                                     <span class="text-danger" title="Suprimé le {{ $post->deleted_at }}">
                                         <i>
                                             <i class="bi bi-trash3"></i>
                                             {{ \Carbon\Carbon::parse($post->deleted_at)->diffForHumans() }}
                                         </i>
                                     </span> |
                                 @else
                                     <span class="text-warning">
                                         <i>
                                             <i class="bi bi-alarm"></i>
                                             {{ $post->created_at }}
                                         </i>
                                     </span>
                                 @endif
                                 @if ($post->updated_price_at)
                                     <div class="text-primary">
                                         <i>
                                             <i class="bi bi-pencil-square"></i> Modifié le :
                                             {{ $post->updated_price_at }}
                                         </i>
                                     </div>
                                 @endif
                             </span>
                         </td>
                         <td>
                             <span class="small text-muted">
                                 {{ $post->etat }}
                             </span>
                         </td>
                         <td>
                             <span title="Auteur"
                                 onclick="document.location.href='/admin/client/{{ $post->user_info->id }}/view'">
                                 <i class="bi bi-person"></i>
                                 <b class="cusor">{{ $post->user_info->username}}</b>
                             </span>
                             <br>
                             <span class="text-primary cusor small"
                                 onclick="OpenModalMessage('{{ $post->id }}','{{ $post->user_info->username }}')">
                                 <i class="bi bi-envelope-fill"></i>
                                 écrire
                             </span>
                         </td>
                         <td>
                             <span class="small text-muted">
                                 {{ $post->sous_categorie_info->categorie->titre }}
                             </span>
                         </td>

                         <td>
                             <i class="bi bi-heart-fill text-danger"></i>
                             {{ $post->getLike->count() }}
                         </td>
                         <th>
                             <a href="{{ route('liste_signalement_publications', ['post_id' => $post->id]) }}"
                                 class="text-warning">
                                 <i class="bi bi-exclamation-triangle "></i>
                                 {{ $post->signalements->count() }}
                             </a>
                         </th>
                         <td class="small ">
                             @if ($post->old_prix)
                                 <span class="strong color strong">
                                     <strike>
                                         {{ $post->getOldPrix() }}
                                     </strike> DH
                                 </span>
                                 <br>
                                 <span class="text-danger strong">
                                     {{ $post->getPrix() }} DH
                                 </span>
                             @else
                                 <span class=" color strong ">
                                     {{ $post->getPrix() }} DH
                                 </span>
                             @endif
                         </td>
                         <td> {{ $post->region->nom ?? 'N/A' }} </td>
                         <td>
                             <span class="text-capitalize">
                                 {{ $post->statut }}
                             </span>
                         </td>
                         <td>
                             {{ $post->updated_price_at ?? '-' }}
                         </td>
                         <td class="text-end">
                             <div class="btn-group">
                                 @if ($post->deleted_at)
                                     <button class="btn btn-sm btn-success" wire:click="restore({{ $post->id }})">
                                         <i class="bi bi-upload"></i> &nbsp; Restorer
                                     </button>
                                 @endif
                                 @if ($type == 'attente')
                                     <button type="button" class="btn btn-sm btn-success"
                                         wire:click="valider({{ $post->id }})">
                                         <i class="bi bi-check-all"></i> &nbsp; Valider
                                     </button>
                                     <button type="button" class="btn btn-sm btn-danger"
                                         wire:click="delete({{ $post->id }})">
                                         <i class="bi bi-x-lg"></i> &nbsp; Réfuser
                                     </button>
                                 @endif
                                 @if ($type == 'publiés')
                                     <button class="btn btn-sm btn-warning" wire:click="vendu({{ $post->id }})">
                                         <i class="bi bi-cart-check"></i> &nbsp; vendu
                                     </button>
                                 @endif

                                 <button class="btn btn-sm btn-dark"
                                     onclick="document.location.href='/admin/publication/{{ $post->id }}/view'">
                                     <i class="bi bi-eye"></i>
                                 </button>
                                 @if ($post->deleted_at)
                                     <button type="button" class="btn btn-sm btn-danger "
                                         wire:click="delete_definitivement({{ $post->id }})">
                                         <i class="ti ti-trash me-1"></i>
                                         &nbsp; Définitivement
                                     </button>
                                 @else
                                     <button type="button" class="btn btn-sm btn-danger"
                                         onclick="OpenModalDeletePost({{ $post->id }})">
                                         <i class="ti ti-trash me-1"></i>
                                     </button>
                                 @endif
                             </div>
                         </td>
                     </tr>
                 @empty
                     <tr>
                         <td colspan="10">
                             <div class="p-3 text-center">
                                 Aucune publication trouvé!
                             </div>
                         </td>
                     </tr>
                 @endforelse
             </tbody>
         </table>
         <div class="p-3" {{ $posts->links('pagination::bootstrap-4') }} </div>
         </div>
     </div>
     <!--/ Ajax Sourced Server-side -->
