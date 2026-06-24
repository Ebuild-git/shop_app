<div>
    {{-- Top bar --}}
    <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            class="form-control flex-grow-1"
            placeholder="Rechercher par nom, email...">

        @if($selectedUserId ?? false)
            <button wire:click="closeUser" class="btn btn-outline-secondary flex-shrink-0">
                <i class="bi bi-arrow-left me-1"></i> Tous les utilisateurs
            </button>
        @endif

        <button
            wire:click="toggleTrashed"
            class="btn {{ $showTrashed ? 'btn-danger' : 'btn-outline-secondary' }} flex-shrink-0">
            <i class="bi bi-trash me-1"></i>
            {{ $showTrashed ? 'Boîte de réception' : 'Corbeille' }}
            @if(!$showTrashed && $trashedCount > 0)
                <span class="badge bg-danger ms-1">{{ $trashedCount }}</span>
            @endif
        </button>

        <button onclick="confirmDeleteAll()" class="btn btn-outline-danger flex-shrink-0">
            <i class="bi bi-trash3 me-1"></i>
            {{ $showTrashed ? 'Vider la corbeille' : 'Tout supprimer' }}
        </button>
    </div>

    @if($selectedMessage ?? false)
        {{-- ── Detail View ── --}}
        <div class="card">
            <div class="card-header d-flex align-items-center gap-3 border-bottom">
                <button wire:click="closeMessage" class="btn btn-sm btn-light">
                    <i class="bi bi-arrow-left"></i> Retour
                </button>

                {{-- Badge type --}}
                @if($selectedMessage['_type'] === 'admin')
                    <span class="badge bg-primary">Message interne</span>
                @else
                    <span class="badge bg-success">Formulaire contact</span>
                @endif

                <h6 class="mb-0 flex-grow-1">
                    {{ $selectedMessage['_type'] === 'admin' ? $selectedMessage->sujet : $selectedMessage->subject }}
                </h6>

                @if($selectedMessage->deleted_at)
                    <button onclick="confirmRestoreMessage({{ $selectedMessage->id }}, '{{ $selectedMessage['_type'] }}')"
                        class="btn btn-sm btn-outline-success">
                        <i class="bi bi-arrow-counterclockwise"></i> Restaurer
                    </button>
                    <button onclick="confirmForceDeleteMessage({{ $selectedMessage->id }}, '{{ $selectedMessage['_type'] }}')"
                        class="btn btn-sm btn-danger">
                        <i class="bi bi-trash"></i> Supprimer définitivement
                    </button>
                @else
                    <button onclick="confirmDeleteMessage({{ $selectedMessage->id }}, '{{ $selectedMessage['_type'] }}')"
                        class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                @endif
            </div>

            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                        style="width:42px;height:42px;font-size:18px;flex-shrink:0">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        @if($selectedMessage['_type'] === 'admin')
                            <div class="fw-semibold">
                                {{ $selectedMessage->sender?->username ?? '—' }}
                                <span class="text-muted fw-normal">→</span>
                                {{ $selectedMessage->recipient?->username ?? '—' }}
                            </div>
                            <small class="text-muted">
                                {{ $selectedMessage->recipient?->email ?? '—' }}
                                · {{ $selectedMessage->created_at->format('d/m/Y à H:i') }}
                            </small>
                        @else
                            <div class="fw-semibold">
                                {{ $selectedMessage->name }}
                                @if($selectedMessage->user_id)
                                    <span class="badge bg-secondary ms-1" style="font-size:10px">Auth</span>
                                @else
                                    <span class="badge bg-light text-dark border ms-1" style="font-size:10px">Invité</span>
                                @endif
                            </div>
                            <small class="text-muted">
                                {{ $selectedMessage->email }}
                                · {{ $selectedMessage->created_at->format('d/m/Y à H:i') }}
                            </small>
                        @endif

                        @if($selectedMessage->deleted_at)
                            <div>
                                <span class="badge bg-danger mt-1">
                                    <i class="bi bi-trash me-1"></i>
                                    Supprimé le {{ $selectedMessage->deleted_at->format('d/m/Y à H:i') }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="p-4 rounded" style="background:#f8f9fa;white-space:pre-wrap;font-size:15px;line-height:1.7">
                    {{ $selectedMessage['_type'] === 'admin' ? $selectedMessage->message : $selectedMessage->message }}
                </div>

                @if($selectedMessage['_type'] === 'admin' && $selectedMessage->post)
                    <div class="mt-4">
                        <small class="text-muted d-block mb-1">Article concerné</small>
                        <a href="/admin/publication/{{ $selectedMessage->post_id }}/view" target="_blank" class="text-decoration-none">
                            <i class="bi bi-box-arrow-up-right me-1"></i>{{ $selectedMessage->post->titre }}
                        </a>
                    </div>
                @endif
            </div>
        </div>

    @elseif($selectedUserId ?? false)
    {{-- ── User's Messages List ── --}}
    <div class="card mb-3">
        <div class="card-body py-2 d-flex align-items-center gap-3">
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0"
                style="width:38px;height:38px">
                <i class="bi bi-person-fill"></i>
            </div>
            <div>
                <div class="fw-semibold">{{ $user->username }}</div>
                <small class="text-muted">{{ $user->email }}</small>
            </div>
            <span class="badge bg-secondary ms-auto">{{ count($userMessages) }} message(s)</span>
        </div>
    </div>

    <div class="card">
        @forelse($userMessages as $msg)
            <div
                class="d-flex align-items-start gap-3 px-4 py-3 border-bottom {{ $msg['deleted_at'] ? 'opacity-75' : '' }}"
                style="transition:background .15s"
                onmouseover="this.style.background='#f5f5f5'"
                onmouseout="this.style.background=''">

                <div class="rounded-circle {{ $msg['_type'] === 'admin' ? 'bg-primary' : 'bg-success' }} text-white d-flex align-items-center justify-content-center flex-shrink-0"
                    style="width:38px;height:38px;font-size:16px">
                    <i class="bi {{ $msg['_type'] === 'admin' ? 'bi-chat-fill' : 'bi-envelope-fill' }}"></i>
                </div>

                {{-- Clickable content --}}
                <div
                    class="flex-grow-1 overflow-hidden"
                    wire:click="viewMessage({{ $msg['id'] }}, '{{ $msg['_type'] }}')"
                    style="cursor:pointer">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge {{ $msg['_type'] === 'admin' ? 'bg-primary' : 'bg-success' }} me-2" style="font-size:10px">
                            {{ $msg['_type'] === 'admin' ? 'Interne' : 'Contact' }}
                        </span>
                        <small class="text-muted ms-auto">
                            {{ \Carbon\Carbon::parse($msg['created_at'])->format('d/m/Y H:i') }}
                        </small>
                    </div>
                    <div class="fw-semibold text-dark mt-1" style="font-size:14px">
                        {{ $msg['_type'] === 'admin' ? $msg['sujet'] : $msg['subject'] }}
                    </div>
                    <div class="text-muted text-truncate" style="font-size:13px">
                        {{ \Illuminate\Support\Str::limit($msg['message'], 80) }}
                    </div>
                    @if($msg['deleted_at'])
                        <span class="badge bg-danger mt-1" style="font-size:10px">
                            <i class="bi bi-trash me-1"></i>
                            Supprimé le {{ \Carbon\Carbon::parse($msg['deleted_at'])->format('d/m/Y à H:i') }}
                        </span>
                    @endif
                </div>

                <i class="bi bi-chevron-right text-muted flex-shrink-0 align-self-center"
                    wire:click="viewMessage({{ $msg['id'] }}, '{{ $msg['_type'] }}')"
                    style="cursor:pointer"></i>

                {{-- Action button: restore or delete --}}
                @if($msg['deleted_at'])
                    <button
                        onclick="confirmRestoreUserMessage({{ $msg['id'] }}, '{{ $msg['_type'] }}')"
                        class="btn btn-sm btn-outline-success flex-shrink-0 align-self-center"
                        title="Restaurer">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                @else
                    <button
                        onclick="confirmDeleteUserMessage({{ $msg['id'] }}, '{{ $msg['_type'] }}')"
                        class="btn btn-sm btn-outline-danger flex-shrink-0 align-self-center"
                        title="Supprimer">
                        <i class="bi bi-trash"></i>
                    </button>
                @endif
            </div>
        @empty
            <div class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                Aucun message pour cet utilisateur.
            </div>
        @endforelse
    </div>

    @else
        {{-- ── Users List ── --}}
        <div class="card mb-4">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="bi bi-people me-2"></i>Utilisateurs
                    <span class="badge bg-secondary ms-2">{{ $users->total() }}</span>
                </h5>
            </div>

            @forelse($users as $user)
                <div
                    wire:click="viewUser({{ $user->id }})"
                    class="d-flex align-items-center gap-3 px-4 py-3 border-bottom"
                    style="cursor:pointer;transition:background .15s"
                    onmouseover="this.style.background='#f5f5f5'"
                    onmouseout="this.style.background=''">

                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:38px;height:38px">
                        <i class="bi bi-person-fill"></i>
                    </div>

                    <div class="flex-grow-1">
                        {{-- Sender --}}
                        <div class="fw-semibold">
                            {{ $user->username }}
                            @if($user->deleted_at)
                                <span class="badge bg-danger ms-1" style="font-size:9px">supprimé</span>
                            @endif
                        </div>
                        <small class="text-muted">{{ $user->email }}</small>

                        {{-- Recipient of last admin message --}}
                        @php $lastMsg = $user->adminMessagesSent->first(); @endphp
                        @if($lastMsg?->recipient)
                            <div class="mt-1 d-flex align-items-center gap-1">
                                <i class="bi bi-arrow-right text-muted" style="font-size:11px"></i>
                                <small class="text-muted">
                                    Destinataire :
                                    <span class="fw-semibold text-dark">{{ $lastMsg->recipient->username }}</span>
                                    @if($lastMsg->recipient->deleted_at)
                                        <span class="badge bg-danger ms-1" style="font-size:9px">supprimé</span>
                                    @endif
                                </small>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex gap-2 flex-shrink-0">
                        @if($user->admin_messages_count > 0)
                            <span class="badge bg-primary">
                                <i class="bi bi-chat me-1"></i>{{ $user->admin_messages_count }}
                            </span>
                        @endif
                        @if($user->contact_messages_count > 0)
                            <span class="badge bg-success">
                                <i class="bi bi-envelope me-1"></i>{{ $user->contact_messages_count }}
                            </span>
                        @endif
                    </div>

                    <i class="bi bi-chevron-right text-muted flex-shrink-0"></i>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="bi bi-people fs-1 d-block mb-2"></i>
                    Aucun utilisateur trouvé.
                </div>
            @endforelse

            <div class="card-footer">
                {{ $users->links('pagination::bootstrap-4') }}
            </div>
        </div>

        {{-- ── Guest Contacts ── --}}
        @if($guestContacts->count())
            <div class="card">
                <div class="card-header border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">
                        <i class="bi bi-person-dash me-2"></i>Contacts invités (non connectés)
                        <span class="badge bg-secondary ms-2">{{ $guestContacts->total() }}</span>
                    </h5>
                    <button
                        onclick="confirmDeleteAllGuests()"
                        class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash3 me-1"></i>
                        {{ $showTrashed ? 'Vider (invités)' : 'Tout supprimer (invités)' }}
                    </button>
                </div>

                @foreach($guestContacts as $contact)
                    <div
                        wire:click="viewMessage({{ $contact->id }}, 'contact')"
                        class="d-flex align-items-start gap-3 px-4 py-3 border-bottom"
                        style="cursor:pointer;transition:background .15s"
                        onmouseover="this.style.background='#f5f5f5'"
                        onmouseout="this.style.background=''">

                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:38px;height:38px">
                            <i class="bi bi-envelope-fill"></i>
                        </div>

                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold">{{ $contact->name }}</span>
                                <small class="text-muted">{{ $contact->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <small class="text-muted">{{ $contact->email }}</small>
                            <div class="fw-semibold text-dark mt-1" style="font-size:14px">{{ $contact->subject }}</div>
                            <div class="text-muted text-truncate" style="font-size:13px">
                                {{ \Illuminate\Support\Str::limit($contact->message, 80) }}
                            </div>
                        </div>

                        <i class="bi bi-chevron-right text-muted flex-shrink-0 align-self-center"></i>

                        @if($contact->deleted_at)
                            <button
                                onclick="confirmRestoreGuest({{ $contact->id }})"
                                class="btn btn-sm btn-outline-success flex-shrink-0 align-self-center"
                                title="Restaurer">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                        @else
                            <button
                                onclick="confirmDeleteGuest({{ $contact->id }})"
                                class="btn btn-sm btn-outline-danger flex-shrink-0 align-self-center"
                                title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </button>
                        @endif
                    </div>
                @endforeach

                <div class="card-footer">
                    {{ $guestContacts->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif
    @endif

    <script>
        function confirmDeleteMessage(id, type) {
            Swal.fire({
                title: 'Supprimer ce message ?',
                text: 'Il sera déplacé dans la corbeille.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
            }).then(r => r.isConfirmed && @this.call('deleteMessage', id, type));
        }

        function confirmRestoreMessage(id, type) {
            Swal.fire({
                title: 'Restaurer ce message ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Oui, restaurer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
            }).then(r => r.isConfirmed && @this.call('restoreMessage', id, type));
        }

        function confirmForceDeleteMessage(id, type) {
            Swal.fire({
                title: 'Supprimer définitivement ?',
                text: 'Cette action est irréversible.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
            }).then(r => r.isConfirmed && @this.call('forceDeleteMessage', id, type));
        }

        function confirmDeleteAll() {
            const isTrashed = @json($showTrashed);
            Swal.fire({
                title: isTrashed ? 'Vider la corbeille ?' : 'Tout supprimer ?',
                text: isTrashed
                    ? 'Tous les messages supprimés seront définitivement perdus.'
                    : 'Tous les messages seront déplacés dans la corbeille.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirmer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
            }).then(r => r.isConfirmed && @this.call('deleteAll'));
        }

        function confirmDeleteGuest(id) {
            Swal.fire({
                title: 'Supprimer ce contact invité ?',
                text: 'Il sera déplacé dans la corbeille.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
            }).then(r => r.isConfirmed && @this.call('deleteGuestContact', id));
        }

        function confirmDeleteAllGuests() {
            const isTrashed = @json($showTrashed);
            Swal.fire({
                title: isTrashed ? 'Vider les invités ?' : 'Supprimer tous les invités ?',
                text: isTrashed
                    ? 'Tous les contacts invités supprimés seront définitivement perdus.'
                    : 'Tous les contacts invités seront déplacés dans la corbeille.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirmer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
            }).then(r => r.isConfirmed && @this.call('deleteAllGuestContacts'));
        }

        function confirmRestoreGuest(id) {
            Swal.fire({
                title: 'Restaurer ce contact invité ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Oui, restaurer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
            }).then(r => r.isConfirmed && @this.call('restoreGuestContact', id));
        }

        function confirmDeleteUserMessage(id, type) {
            Swal.fire({
                title: 'Supprimer ce message ?',
                text: 'Il sera déplacé dans la corbeille.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
            }).then(r => r.isConfirmed && @this.call('deleteMessage', id, type));
        }

        function confirmRestoreUserMessage(id, type) {
            Swal.fire({
                title: 'Restaurer ce message ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Oui, restaurer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
            }).then(r => r.isConfirmed && @this.call('restoreMessage', id, type));
        }
    </script>
</div>
