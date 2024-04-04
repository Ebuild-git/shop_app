 <!-- Ajax Sourced Server-side -->
 <div class="card ">
     <div class="row p-3 card-header">
         <div class="col-sm-4 my-auto">
             <h5 class="">
                 Liste des utilisateurs du site
             </h5>
         </div>
         <div class="col-sm-8 my-auto">
             <form wire:submit="filtre">
                 <div class="input-group mb-3">
                     <input type="text" class="form-control" wire:model.live="key" placeholder="Nom,Email,Téléphone">
                     <select wire:model ="statut" class="form-control">
                         <option value="" selected>Tous les statuts</option>
                         <option value="1">Verifié</option>
                         <option value="0">Non verifié</option>
                     </select>
                     <button class="btn btn-primary" type="submit" id="button-addon2">
                         <span wire:loading>
                             <x-loading></x-loading>
                         </span>
                         <i class="fa-solid fa-filter"></i> &nbsp;
                         Filtrer
                     </button>
                     <button class="btn btn-dark ">
                         <a href="{{ route('export_users') }}" style="color: white !important;">
                             <i class="bi bi-file-earmark-excel"></i>
                             Exporter la liste
                         </a>
                     </button>
                 </div>
             </form>
         </div>

         <div class="d-flex justify-content-between">

         </div>


     </div>

     <div class="card-datatable text-nowrap">
         <table class="datatables-ajax table">
             <thead class="table-dark">
                 <tr>
                     <th></th>
                     <th>Full name</th>
                     <th>Email</th>
                     <th>Téléphone</th>
                     <th>Publications</th>
                     <th>Verifié</th>
                     <th>Ville</th>
                     <th>Action</th>
                     <td></td>
                 </tr>
             </thead>

             <tbody>
                 @forelse ($users as $user)
                     <tr>
                         <td class="avatar">
                             <div class="avatar me-3">
                                 <img src="{{ $user->getAvatar() }}" alt="..." class="rounded-circle">
                             </div>
                         </td>
                         <td> {{ $user->name }} </td>
                         <td> {{ $user->email }} </td>
                         <td> {{ $user->phone_number ?? '/' }} </td>
                         <td> {{ $user->GetPosts->count() }} </td>
                         <td>
                             @if ($user->email_verified_at == null)
                                 <span class="badge bg-label-danger">
                                     Non
                                 </span>
                             @else
                                 <span class="badge bg-label-success">
                                     Oui
                                 </span>
                             @endif
                         </td>
                         <td> {{ $user->ville ?? '/' }} </td>
                         <td>
                             <button class="btn btn-sm"
                                 onclick="document.location.href='/admin/client/{{ $user->id }}/view'">
                                 <i class="ti ti-eye me-1"></i>Voir plus</a>
                             </button>
                         </td>
                         <td>
                             <div class="dropdown">
                                 <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                     data-bs-toggle="dropdown">
                                     <i class="ti ti-dots-vertical"></i>
                                 </button>
                                 <div class="dropdown-menu">
                                     <a class="dropdown-item text-danger" href="javascript:void(0)"
                                         wire:click="delete( {{ $user->id }})">
                                         <i class="ti ti-trash me-1"></i> Supprimer </a>
                                 </div>
                             </div>
                         </td>


                     </tr>
                 @empty
                     <tr>
                         <td colspan="8">
                             <div class="p-3">
                                 Aucun utilisateur trouvé!
                             </div>
                         </td>
                     </tr>
                 @endforelse
             </tbody>
         </table>
         <div class="p-3" {{ $users->links('pagination::bootstrap-4') }} </div>
         </div>
     </div>
     <!--/ Ajax Sourced Server-side -->
