 <!-- Ajax Sourced Server-side -->
 <div class="card ">
     <div class="row p-3 card-header">
         <div class="col-sm-4 my-auto">
             <h5 class="">

                @if($locked === 'yes')
                    Liste des utilisateurs bloqués ({{ $users->total() }})
                @elseif($showTrashed === 'yes')
                    Liste des utilisateurs supprimés ({{ $users->total() }})
                @else
                    Liste des utilisateurs du site ({{ $users->total() }})
                @endif
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
                     {{-- <select wire:model ="etat" class="form-control">
                         <option value="">Tous les étas</option>
                         <option value="1">Bloquer</option>
                         <option value="0">Actif</option>
                     </select> --}}
                    @if($showTrashed == 'yes')
                     <select wire:model="deletedBy" class="form-control">
                        <option value="">Tous les comptes</option>
                        <option value="shopin">Supprimés par Shopin</option>
                        <option value="self">Supprimés par l'utilisateur</option>
                    </select>
                    @endif

                     <button class="btn btn-primary" type="submit" id="button-addon2">
                         <span wire:loading>
                             <x-loading></x-loading>
                         </span>
                         <i class="fa-solid fa-filter"></i> &nbsp;
                         Filtrer
                     </button>

                     <button class="btn btn-dark" onclick="exportTableToXLSX('user_list.xlsx')" style="color: white !important;">
                        <i class="bi bi-file-earmark-excel"></i>
                        Exporter la liste
                    </button>

                 </div>
             </form>
         </div>

     </div>

     @include('components.alert-livewire')

     <div class="card">
        <div class="card-body">
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">

         <table class="table">

                 <tr>
                    <th>ID</th>
                    <th style="left: 50px;">Pseudonyme</th>
                     <th style="left: 160px;">Prénom</th>
                     <th>Nom</th>
                     <th>Inscription</th>
                     <th>Dernière connexion</th>
                     @if($showTrashed !== 'yes')
                     <th>Nb articles en vente</th>
                     <th>Total des ventes</th>
                     <th>Évaluation</th>
                     @endif
                    <th>Statut du compte</th>
                    @if($showTrashed !== 'yes')
                    <th>Statut des informations RIB</th>
                    @endif
                     <th>Vérification d'identité</th>
                     <th>Email</th>
                     <th>Téléphone</th>
                     <th>Adresse</th>
                     @if($showTrashed !== 'yes')
                     <th>Historique des violations</th>
                     @endif
                     <th>Actions</th>
                    <th></th>
                 </tr>


             <tbody>
                 @forelse ($users as $user)
                     <tr>
                        <td>{{ 'U' . ($user->id + 1000) }}</td>
                        @if($showTrashed !== 'yes')
                            <td style="left: 50px;"> {{ $user->username }} </td>
                        @else
                            <td style="left: 50px;"> {{ $user->username_deleted }} </td>
                        @endif
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
                        @if($showTrashed !== 'yes')
                        <td style="text-align: center;"> {{ $user->GetPosts->count() }}</td>

                        <td style="text-align: center;">
                            {{ $venduCountPerUser[$user->id] }}
                        </td>
                        <td>
                            <i class="bi bi-star-fill" style="color: #ffb74e;"></i>
                            {{ number_format($user->averageRating->average_rating ?? 0, 1) }}
                        </td>
                        @endif
                        <td style="text-align: center;">
                            @if($user->locked)
                                suspendu
                            @elseif($user->deleted_at)
                                <span class="text-danger">supprimé</span>
                            @else
                                <span class="text-success">actif</span>
                            @endif
                        </td>
                        @if($showTrashed !== 'yes')
                        <td>
                            @if (empty($user->rib_number) && empty($user->bank_name) && empty($user->titulaire_name))
                                <span class="badge bg-danger">Non fournies</span>
                            @elseif (!empty($user->rib_number) && !empty($user->bank_name) && !empty($user->titulaire_name))
                                <span class="badge bg-success">Fournies</span>
                            @elseif (empty($user->rib_number) || empty($user->bank_name) || empty($user->titulaire_name))
                                <span class="badge bg-warning text-dark">Incompletes</span>
                            @elseif ($user->rib_status === 'rejected')
                                <span class="badge bg-dark">Rejetées</span>
                            @endif
                        </td>
                        @endif
                        <td>
                            @if($user->isIdentityVerified())
                            <span class="text-success">Vérifié</span>
                            @else
                                <span class="text-danger">Non vérifié</span>
                            @endif
                        </td>
                        @if($showTrashed !== 'yes')
                        <td>
                            <span style="cursor: pointer;" onclick="OpenModalMessage('{{ $user->id }}','{{ $user->username }}')">
                                {{ $user->email }}
                            </span>
                            <br>
                            <span style="font-size: 12px; color: #008080; cursor: pointer;" onclick="OpenModalMessage('{{ $user->id }}','{{ $user->username }}')">
                                <i class="bi bi-chat-left-text-fill" style="margin-right: 5px;"></i> Message
                            </span>
                        </td>
                        @else
                            <td>
                                <span style="cursor: pointer;">
                                    {{ $user->email ?? $user->email_deleted}}
                                </span>
                                <br>
                                <span style="font-size: 12px; color: #008080; cursor: pointer;" onclick="OpenModalMessage('{{ $user->id }}','{{ $user->username }}')">
                                    <i class="bi bi-chat-left-text-fill" style="margin-right: 5px;"></i> Message
                                </span>
                            </td>
                        @endif
                        <td> {{ $user->phone_number ?? '/' }} </td>
                        <td>
                            {!! $user->num_appartement ? 'App. ' . $user->num_appartement . '<br>' : '' !!}
                            {!! $user->etage ? 'Étage ' . $user->etage . '<br>' : '' !!}
                            {!! $user->nom_batiment ? $user->nom_batiment . '<br>' : '' !!}
                            {!! $user->rue ? $user->rue . '<br>' : '' !!}
                            {!! $user->address ?? '' !!}
                        </td>
                        @if($showTrashed !== 'yes')
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-sm btn-dark d-flex align-items-center" style="margin-left: 10px;" onclick="window.location.href='{{ route('liste_signalement_by_user', ['user_id' => $user->id]) }}'">
                                    <i class="bi bi-clock-history" style="font-size: 14px;"></i>
                                </button>
                            </td>
                        @endif
                        <td>
                            @if($showTrashed !== 'yes')
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-dark"
                                        onclick="document.location.href='/admin/client/{{ $user->id }}/view'">
                                        <i class="bi bi-person-circle"></i>
                                    </button>

                                    @if ($user->locked == true)
                                        <button class="btn btn-sm btn-success" wire:click="toggleLock({{ $user->id }})"
                                            type="button" title="Débloquer cet utilisateur">
                                            <i class="bi bi-play-fill"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-danger" wire:click="toggleLock({{ $user->id }})"
                                            type="button" title="Bloquer cet utilisateur">
                                            <i class="bi bi-stop-fill"></i>
                                        </button>
                                    @endif
                                </div>
                            @else
                                <button class="btn btn-sm btn-dark"
                                    onclick="document.location.href='/admin/client/{{ $user->id }}/view'">
                                    <i class="bi bi-person-circle"></i>
                                </button>
                                @if(!$user->email_deleted)
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-success" onclick="confirmRestoreUser({{ $user->id }})">
                                            Restaurer
                                        </button>
                                    </div>
                                @endif
                            @endif
                        </td>

                        <td>
                            @if($showTrashed !== 'yes')
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
                            @else
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <button class="btn btn-sm btn-danger" onclick="confirmForceDeleteUser({{ $user->id }})">
                                            Supprimer Définitivement
                                        </button>
                                    </div>
                                </div>
                            @endif
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


<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    function exportTableToXLSX(filename) {
        var table = document.querySelector("table");
        var workbook = XLSX.utils.book_new();
        var worksheet = XLSX.utils.table_to_sheet(table, { raw: true });

        // Remove the last two columns (Actions and the second last)
        var lastColumnIndex = XLSX.utils.decode_range(worksheet['!ref']).e.c; // Get last column index
        for (var row = 0; row <= XLSX.utils.decode_range(worksheet['!ref']).e.r; row++) {
            delete worksheet[XLSX.utils.encode_cell({ c: lastColumnIndex, r: row })]; // Delete last column
            delete worksheet[XLSX.utils.encode_cell({ c: lastColumnIndex - 1, r: row })]; // Delete second last column
        }

        // Adjust column widths based on content
        const columnWidths = [];
        for (let col = 0; col < lastColumnIndex - 1; col++) { // Now lastColumnIndex - 1 since we deleted 2 columns
            let maxWidth = 10; // Set a minimum width
            for (let row = 0; row <= XLSX.utils.decode_range(worksheet['!ref']).e.r; row++) {
                const cell = worksheet[XLSX.utils.encode_cell({ c: col, r: row })];
                if (cell && cell.v) {
                    maxWidth = Math.max(maxWidth, cell.v.toString().length);
                }
            }
            columnWidths.push({ wpx: maxWidth * 10 }); // Multiply by a factor for better spacing
        }

        worksheet['!cols'] = columnWidths; // Set column widths

        XLSX.utils.book_append_sheet(workbook, worksheet, "Users");
        XLSX.writeFile(workbook, filename);
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        function confirmRestore(userId) {
            Swal.fire({
                title: 'Restaurer cet utilisateur ?',
                text: "Êtes-vous sûr de vouloir le restaurer ?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Oui, restaurer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.restore(userId);
                }
            });
        }

        function confirmForceDelete(userId) {
            Swal.fire({
                title: 'Supprimer définitivement ?',
                text: "Cette action est irréversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.forceDelete(userId);
                }
            });
        }

        window.confirmRestoreUser = confirmRestore;
        window.confirmForceDeleteUser = confirmForceDelete;

    });
</script>

<script>
    window.addEventListener('swal:success', event => {
    Swal.fire({
        title: event.detail[0].title,
        text: event.detail[0].text,
        icon: event.detail[0].icon,
        timer: 2500,
        showConfirmButton: false
    });
});
</script>
