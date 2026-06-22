<div>
    {{-- Top bar --}}
    <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            class="form-control flex-grow-1"
            placeholder="Rechercher par sujet, message ou destinataire...">

        <button
            wire:click="toggleTrashed"
            class="btn {{ $showTrashed ? 'btn-danger' : 'btn-outline-secondary' }} flex-shrink-0">
            <i class="bi bi-trash me-1"></i>
            {{ $showTrashed ? 'Boîte de réception' : 'Corbeille' }}
            @if(!$showTrashed && $trashedCount > 0)
                <span class="badge bg-danger ms-1">{{ $trashedCount }}</span>
            @endif
        </button>

        <button
            onclick="confirmDeleteAll()"
            class="btn btn-outline-danger flex-shrink-0">
            <i class="bi bi-trash3 me-1"></i>
            {{ $showTrashed ? 'Vider la corbeille' : 'Tout supprimer' }}
        </button>
    </div>

    @if($selectedMessage)
        {{-- ── Detail View ── --}}
        <div class="card">
            <div class="card-header d-flex align-items-center gap-3 border-bottom">
                <button wire:click="closeMessage" class="btn btn-sm btn-light">
                    <i class="bi bi-arrow-left"></i> Retour
                </button>
                <h6 class="mb-0 flex-grow-1">{{ $selectedMessage->sujet }}</h6>

                @if($selectedMessage->deleted_at)
                    <button
                        onclick="confirmRestoreMessage({{ $selectedMessage->id }})"
                        class="btn btn-sm btn-outline-success">
                        <i class="bi bi-arrow-counterclockwise"></i> Restaurer
                    </button>
                    <button
                        onclick="confirmForceDeleteMessage({{ $selectedMessage->id }})"
                        class="btn btn-sm btn-danger">
                        <i class="bi bi-trash"></i> Supprimer définitivement
                    </button>
                @else
                    <button
                        onclick="confirmDeleteMessage({{ $selectedMessage->id }})"
                        class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                @endif
            </div>

            <div class="card-body">
                {{-- Participants --}}
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                        style="width:42px;height:42px;font-size:18px;flex-shrink:0">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <div class="fw-semibold">
                            {{ $selectedMessage->sender?->username ?? '—' }}
                            <span class="text-muted fw-normal">→</span>
                            {{ $selectedMessage->recipient?->username ?? $selectedMessage->recipient?->username_deleted ?? '—' }}
                            @if($selectedMessage->recipient?->deleted_at)
                                <span class="badge bg-danger ms-1" style="font-size:9px">supprimé</span>
                            @endif
                        </div>
                        <small class="text-muted">
                            {{ $selectedMessage->recipient?->email ?? '—' }}
                            · {{ $selectedMessage->created_at->format('d/m/Y à H:i') }}
                        </small>
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

                {{-- Message body --}}
                <div class="p-4 rounded" style="background:#f8f9fa;white-space:pre-wrap;font-size:15px;line-height:1.7">
                    {{ $selectedMessage->message }}
                </div>

                {{-- Meta --}}
                <div class="mt-4 d-flex flex-wrap gap-4">
                    @if($selectedMessage->post)
                        <div>
                            <small class="text-muted d-block mb-1">Article concerné</small>
                            <a href="/admin/publication/{{ $selectedMessage->post_id }}/view" target="_blank" class="text-decoration-none">
                                <i class="bi bi-box-arrow-up-right me-1"></i>{{ $selectedMessage->post->titre }}
                            </a>
                        </div>
                    @endif

                    @if($selectedMessage->sent_from)
                        <div>
                            <small class="text-muted d-block mb-1">Envoyé depuis</small>
                            <a href="{{ $selectedMessage->sent_from }}" target="_blank" class="text-decoration-none small">
                                <i class="bi bi-link-45deg me-1"></i>{{ parse_url($selectedMessage->sent_from, PHP_URL_PATH) }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    @else
        {{-- ── Inbox / Trash List ── --}}
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="bi {{ $showTrashed ? 'bi-trash' : 'bi-envelope' }} me-2"></i>
                    {{ $showTrashed ? 'Corbeille' : 'Messages envoyés' }}
                    <span class="badge bg-secondary ms-2">{{ $messages->total() }}</span>
                </h5>
            </div>

            @forelse ($messages as $msg)
                <div
                    wire:click="viewMessage({{ $msg->id }})"
                    class="d-flex align-items-start gap-3 px-4 py-3 border-bottom {{ $showTrashed ? 'opacity-75' : '' }}"
                    style="cursor:pointer;transition:background .15s"
                    onmouseover="this.style.background='#f5f5f5'"
                    onmouseout="this.style.background=''">

                    {{-- Avatar --}}
                    <div class="rounded-circle {{ $showTrashed ? 'bg-secondary' : 'bg-primary' }} text-white d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:38px;height:38px;font-size:16px">
                        <i class="bi bi-person-fill"></i>
                    </div>

                    {{-- Content --}}
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">
                                {{ $msg->recipient?->username ?? $msg->recipient?->username_deleted ?? '—' }}
                                @if($msg->recipient?->deleted_at)
                                    <span class="badge bg-danger ms-1" style="font-size:9px">supprimé</span>
                                @endif
                            </span>
                            <small class="text-muted flex-shrink-0 ms-2">
                                {{ $msg->created_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                        <div class="fw-semibold text-dark" style="font-size:14px">
                            {{ $msg->sujet }}
                        </div>
                        <div class="text-muted text-truncate" style="font-size:13px">
                            {{ Str::limit($msg->message, 80) }}
                        </div>
                        @if($msg->sent_from)
                            <small class="text-muted">
                                <i class="bi bi-link-45deg"></i>
                                {{ parse_url($msg->sent_from, PHP_URL_PATH) }}
                            </small>
                        @endif
                    </div>

                    <i class="bi bi-chevron-right text-muted flex-shrink-0 align-self-center"></i>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="bi {{ $showTrashed ? 'bi-trash' : 'bi-inbox' }} fs-1 d-block mb-2"></i>
                    {{ $showTrashed ? 'La corbeille est vide.' : 'Aucun message trouvé.' }}
                </div>
            @endforelse

            <div class="card-footer">
                {{ $messages->links('pagination::bootstrap-4') }}
            </div>
        </div>
    @endif

    <script>
        function confirmDeleteMessage(id) {
            Swal.fire({
                title: 'Supprimer ce message ?',
                text: 'Il sera déplacé dans la corbeille.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteMessage', id);
                }
            });
        }

        function confirmRestoreMessage(id) {
            Swal.fire({
                title: 'Restaurer ce message ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Oui, restaurer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('restoreMessage', id);
                }
            });
        }

        function confirmForceDeleteMessage(id) {
            Swal.fire({
                title: 'Supprimer définitivement ?',
                text: 'Cette action est irréversible.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteMessage', id);
                }
            });
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
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteAll');
                }
            });
        }
    </script>
</div>
