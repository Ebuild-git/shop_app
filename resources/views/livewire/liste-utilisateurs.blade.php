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

     </div>

     <div class="card">
        <div class="card-body">
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">

         <table class="table">

                 <tr>
                    <th>ID</th>
                    <th style="left: 50px;">Pseudonyme</th>
                     <th style="left: 160px;">Nom</th>
                     <th>Prénom</th>
                     <th>Inscription</th>
                     <th>Dernière connexion</th>
                     <th>Nb articles en vente</th>
                     <th>Total des ventes</th>
                     <th>Évaluation</th>
                    <th>Statut du compte</th>
                    <th>Statut des informations RIB</th>
                     <th>Vérification d'identité</th>
                     <th>Email</th>
                     <th>Téléphone</th>
                     <th>Adresse</th>
                     <th>Historique des violations</th>
                     <th>Actions</th>
                        <th></th>
                 </tr>


             <tbody>
                 @forelse ($users as $user)
                     <tr>
                        <td>{{ 'U' . ($user->id + 1000) }}</td>
                        <td style="left: 50px;"> {{ $user->username }} </td>
                        <td style="left: 160px;"> {{ $user->firstname }} </td>
                        <td> {{ $user->lastname }} </td>
                        <td>
                            <span title="{{ $user->created_at->diffForHumans() }}">
                                {{ $user->created_at->format('d/m/Y') }}
                            </span>
                        </td>
                        <td>
                            @if($user->last_login_at)
                            @php
                                $lastLogin = \Carbon\Carbon::parse($user->last_login_at);
                            @endphp
                            <span title="{{ $lastLogin->diffForHumans() }}">
                                {{ $lastLogin->format('d/m/Y') }}
                            </span>
                            @else
                                <span title="Aucune donnée de connexion">
                                    Jamais connecté
                                </span>
                            @endif
                        </td>
                        <td style="text-align: center;"> {{ $user->GetPosts->count() }}</td>
                        <td style="text-align: center;">
                            {{ $venduCountPerUser[$user->id] }}
                        </td>
                        <td>
                            <i class="bi bi-star-fill" style="color: #ffb74e;"></i>
                            {{ number_format($user->averageRating->average_rating ?? 0, 1) }}
                        </td>
                        <td style="text-align: center;">
                            @if ($user->locked)
                                suspendu
                            @else
                                actif
                            @endif
                        </td>
                        <td>
                            @if (empty($user->rib_number) && empty($user->bank_name) && empty($user->titulaire_name))
                                <span class="badge bg-danger">Non fournies</span>
                            @elseif (!empty($user->rib_number) && !empty($user->bank_name) && !empty($user->titulaire_name))
                                <span class="badge bg-success">Fournies</span>
                            @elseif (empty($user->rib_number) || empty($user->bank_name) || empty($user->titulaire_name))
                                <span class="badge bg-warning text-dark">Incompletes</span>
                            @elseif ($user->rib_status === 'rejected') <!-- Adjust this condition based on your rejection logic -->
                                <span class="badge bg-dark">Rejetées</span>
                            @endif
                        </td>
                        <td>
                            @if($user->isIdentityVerified())
                            <span class="text-success">Vérifié</span>
                            @else
                                <span class="text-danger">Non vérifié</span>
                            @endif
                        </td>
                        <td>
                            <span style="cursor: pointer;" onclick="OpenModalMessage('{{ $user->id }}','{{ $user->username }}')">
                                {{ $user->email }}
                            </span>
                            <br>
                            <span style="font-size: 12px; color: #008080; cursor: pointer;" onclick="OpenModalMessage('{{ $user->id }}','{{ $user->username }}')">
                                <i class="bi bi-chat-left-text-fill" style="margin-right: 5px;"></i> Message
                            </span>
                        </td>
                        <td> {{ $user->phone_number ?? '/' }} </td>
                        <td>
                            {!! $user->num_appartement ? 'App. ' . $user->num_appartement . '<br>' : '' !!}
                            {!! $user->etage ? 'Étage ' . $user->etage . '<br>' : '' !!}
                            {!! $user->nom_batiment ? $user->nom_batiment . '<br>' : '' !!}
                            {!! $user->rue ? $user->rue . '<br>' : '' !!}
                            {!! $user->address ?? '' !!}
                        </td>

                        <td style="text-align: center;">
                            <button type="button" class="btn btn-sm btn-dark d-flex align-items-center" style="margin-left: 10px;" onclick="window.location.href='{{ route('liste_signalement_by_user', ['user_id' => $user->id]) }}'">
                                <i class="bi bi-clock-history" style="font-size: 14px;"></i>
                            </button>


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
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="confirmDeleteUser({{ $user->id }}, @this)">
                                        <i class="ti ti-trash me-1"></i> Supprimer
                                    </a>
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

         <div class="p-3">{{ $users->links('pagination::bootstrap-4') }} </div>
         </div>
     </div>
 </div>


 <script>
    function confirmDeleteUser(userId, livewireInstance) {
        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: "Cette action est irréversible!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#008080',
            cancelButtonColor: '#000',
            confirmButtonText: 'Oui, supprimer!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                livewireInstance.call('delete', userId);
                Swal.fire(
                    'Supprimé!',
                    'L\'utilisateur a été supprimé.',
                    'success'
                );
            }
        });
    }
</script>



