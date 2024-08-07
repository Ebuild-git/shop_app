 <!-- Ajax Sourced Server-side -->
 <div class="card ">
     <div class="row p-3 card-header">
         <div class="col-sm-4 my-auto">
             <h5 class="">
                 Liste des utilisateurs du site ({{ $users->total() }})
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
                     <select wire:model ="etat" class="form-control">
                         <option value="">Tous les étas</option>
                         <option value="1">Bloquer</option>
                         <option value="0">Actif</option>
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

     <div class="card">
        <div class="card-body">
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">

         <table class="table">
             <thead class="table-dark">
                 <tr>
                    <th>Pseudonyme </th>
                    <th>Email</th>
                     <th>Nom</th>
                     <th>Prénom</th>
                     <th>Téléphone</th>
                     <th>Publications</th>
                     <th>Inscription</th>
                     <th>Actions</th>
                     <th></th>
                 </tr>
             </thead>

             <tbody>
                 @forelse ($users as $user)
                     <tr>
                        <td> {{ $user->username }} </td>
                        <td>
                            <span class="cusor"
                                onclick="OpenModalMessage('{{ $user->email }}','{{ $user->username }}')">
                                {{ $user->email }}
                            </span>
                        </td>
                         <td> {{ $user->lastname }} </td>
                         <td> {{ $user->firstname }} </td>
                         <td> {{ $user->phone_number ?? '/' }} </td>
                         <td> {{ $user->GetPosts->count() }} </td>
                         <td>
                             <span title="{{ $user->created_at->diffForHumans() }}">
                                 {{ $user->created_at->format('d/m/Y') }}
                             </span>
                         </td>
                         <td>
                            <div class="action-buttons">

                             <button class="btn btn-sm btn-dark"
                                 onclick="document.location.href='/admin/client/{{ $user->id }}/view'">
                                 <i class="bi bi-person-circle"></i>
                                 </a>
                             </button>

                             @if ($user->locked == true)
                                 <button class="btn btn-sm btn-success" wire:click="locked({{ $user->id }})"
                                     type="button" title="Débloquer cet utilisateur">
                                     <i class="bi bi-play-fill"></i>
                                 @else
                                     <button class="btn btn-sm btn-danger" wire:click="locked({{ $user->id }})"
                                         type="button" title="Bloquer cet utilisateur">
                                         <i class="bi bi-stop-fill"></i>
                             @endif
                             </button>
                            </div>
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
                         <td colspan="7">
                             <div class="p-3">
                                 Aucun utilisateur trouvé!
                             </div>
                         </td>
                     </tr>
                 @endforelse
             </tbody>
         </table>

        </div>

         <div class="p-3" {{ $users->links('pagination::bootstrap-4') }} </div>
         </div>
     </div>
 </div>
     <!--/ Ajax Sourced Server-side -->

     <style>
        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
            overflow-x: auto;
        }

        .table {
            min-width: 100%;
        }

        .table th,
        .table td {
            white-space: nowrap;
            text-align: left;
            padding: 8px 12px;
        }

        .table thead th {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #343a40;
            color: #fff;
            box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
        }

        .table tbody td {
            border-bottom: 1px solid #dee2e6;
        }

        .table .action-buttons {
            display: flex;
            gap: 5px;
            align-items: center;
        }
     </style>
