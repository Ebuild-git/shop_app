 <!-- Ajax Sourced Server-side -->


 <div class="card">
     <div class="row p-2">
         <div class="col-sm-12 my-auto">
             <h5 class="card-header">
                 Liste des publications
             </h5>
         </div>
         <div class="col-sm-12 my-auto">
             <form wire:submit="filtre">
                 <div class="input-group mb-3">
                     <input type="text" class="form-control" wire:model="mot_key" placeholder="titre">
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
                             <option value="{{ $item->id }}">{{ $item->titre }}</option>
                         @endforeach
                     </select>
                     <button class="btn btn-primary" type="submit" id="button-addon2">
                         <span wire:loading>
                             <x-loading></x-loading>
                         </span>
                         <i class="fa-solid fa-filter"></i> &nbsp;
                         Filtrer
                     </button>
                 </div>
             </form>
         </div>
     </div>
     @if (session()->has('error'))
         <span class="text-danger small">
             {{ session('error') }}
         </span>
     @enderror
     @if (session()->has('success'))
         <span class="text-success small">
             {{ session('success') }}
         </span>
     @enderror
     <div class="table-responsive text-nowrap">
         <table class="datatables-ajax table">
             <thead class="table-dark">
                 <tr>
                     <th>Titre</th>
                     <th>Likes</th>
                     <th>Prix</th>
                     <th>Régions</th>
                     <th>Statut</th>
                     <th>Actions</th>
                     <td></td>
                 </tr>
             </thead>

             <tbody>
                 @forelse ($posts as $post)
                     <tr>
                         <td>
                             <span class="small">
                                 <strong>
                                    {{ Str::of($post->titre)->limit(20) }}
                                 </strong> <br>
                                 <span class="text-warning">
                                     <i>
                                         <i class="bi bi-alarm"></i>
                                         {{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}
                                     </i>
                                 </span>
                                 | Par
                                 <span
                                     onclick="document.location.href='/admin/client/{{ $post->user_info->id }}/view'">
                                     <i class="bi bi-person"></i>
                                     <b class="cusor">{{ $post->user_info->firstname }}</b>
                                     @if ($post->user_info->certifier == 'oui')
                                         <img width="14" height="14"
                                             src="https://img.icons8.com/sf-regular-filled/48/40C057/approval.png"
                                             alt="approval" title="Certifié" />
                                     @endif
                                 </span>
                             </span>
                         </td>
                         <td>
                             <i class="bi bi-heart-fill text-danger"></i>
                             {{ $post->getLike->count() }}
                         </td>
                         <td> {{ $post->prix ?? '0' }} DT</td>
                         <td> {{ $post->region->nom ?? 'N/A' }} </td>
                         <td>
                             <span class="text-capitalize">
                                 {{ $post->statut }}
                             </span>
                         </td>
                         <td>
                             <button class="btn btn-sm btn-secondary"
                                 onclick="document.location.href='/admin/publication/{{ $post->id }}/view'">
                                 <i class="bi bi-eye"></i> &nbsp; Voir
                             </button>
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
                                 <button class="btn btn-sm btn-warning"
                                     wire:click="vendu({{ $post->id }})">
                                     <i class="bi bi-cart-check"></i> &nbsp; vendu
                                 </button>
                             @endif
                         </td>
                         <td>
                             <div class="dropdown">
                                 <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                     data-bs-toggle="dropdown">
                                     <i class="ti ti-dots-vertical"></i>
                                 </button>
                                 <div class="dropdown-menu">
                                     <a class="dropdown-item text-danger" href="javascript:void(0)"
                                         wire:click="delete({{ $post->id }})">
                                         <i class="ti ti-trash me-1"></i> &nbsp; Supprimer </a>
                                 </div>
                             </div>
                         </td>
                     </tr>
                 @empty
                     <tr>
                         <td colspan="8">
                             <div class="p-3">
                                 Aucun publication trouvé!
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
