<li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
        data-bs-auto-close="outside" aria-expanded="false">
        <i class="ti ti-bell ti-md"></i>
        @if ($notifications->where('statut', 'unread')->count() > 0)
            <span class="badge bg-danger rounded-pill badge-notifications">
                {{ $notifications->where('statut', 'unread')->count() }}
            </span>
        @endif
    </a>

    <ul class="dropdown-menu dropdown-menu-end py-0">
        <li class="dropdown-menu-header border-bottom">
            <div class="dropdown-header d-flex align-items-center py-3">
                <h5 class="text-body mb-0 me-auto">Notifications</h5>
                <a href="javascript:void(0)" wire:click="all_read()" class="dropdown-notifications-all text-body"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Mark all as read">
                    <i class="ti ti-mail-opened fs-4"></i>
                </a>
                <a href="javascript:void(0)" wire:click="deleteAll()" class="dropdown-notifications-all text-danger ms-2"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Supprimer toutes les notifications">
                    <i class="ti ti-trash fs-4"></i>
                </a>
            </div>
        </li>
        <div wire:key="notifications-list-{{ time() }}" class="dropdown-notifications-list scrollable-container">
            <ul class="list-group list-group-flush">
                @forelse ($notifications as $item)
                    <li id="notif-{{ $item->id }}" class="list-group-item list-group-item-action dropdown-notifications-item">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar">

                                    @if ($item->type == 'new_post' || $item->type == 'photo')
                                        @if ($item->getUser)
                                            <img src="{{ $item->getUser->getAvatar() }}" alt class="h-auto rounded-circle" />
                                        @else
                                            <img src="/assets-admin/img/avatars/1.png" alt class="h-auto rounded-circle" />
                                        @endif
                                    @else
                                        <img src="/assets-admin/img/avatars/1.png" alt class="h-auto rounded-circle" />
                                    @endif

                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <a href="{{ route('notifications.read', $item->id) }}">
                                    <h6 class="mb-1">{{ $item->titre }}</h6>
                                </a>

                                <p class="mb-0">{{ $item->message }}</p>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                                </small>

                            </div>

                            <div class="flex-shrink-0 dropdown-notifications-actions">
                                @if($item->statut == "unread")
                                    <a href="javascript:void(0)" class="dropdown-notifications-read">
                                        <span class="badge badge-dot"></span>
                                    </a>
                                @endif
                                <a href="javascript:void(0)"
                                onclick="deleteNotification({{ $item->id }})"
                                class="dropdown-notifications-archive">
                                    <span class="ti ti-x"></span>
                                </a>
                            </div>

                        </div>
                    </li>
                @empty
                    <li class="list-group-item text-center text-primary fw-bold p-3">Aucune notification</li>
                    <audio src="{{ asset('/icons/ping-82822.mp3') }}"></audio>
                @endforelse

            </ul>
        </div>
    </ul>

</li>
<script>
    Pusher.logToConsole = true;

    var pusher = new Pusher('5b6f7ad6a8cf384098d9', {
        cluster: 'eu'
    });

    var channel = pusher.subscribe('my-channel-private-admin');
    channel.bind('my-event', function(data) {
        window.Livewire.dispatch('notificationReceived');

    });
</script>
<script>
    function deleteNotification(id) {
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Cette notification sera supprimée définitivement.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/delete/notifications/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const notifElement = document.getElementById(`notif-${id}`);
                        if (notifElement) notifElement.remove();

                        const badge = document.querySelector('.badge-notifications');
                        if (badge) {
                            const currentCount = parseInt(badge.textContent);
                            if (currentCount > 1) {
                                badge.textContent = currentCount - 1;
                            } else {
                                badge.remove();
                            }
                        }

                        Swal.fire({
                            title: 'Supprimée !',
                            text: 'La notification a été supprimée.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire('Erreur', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur AJAX:', error);
                    Swal.fire('Erreur', 'Une erreur est survenue.', 'error');
                });
            }
        });
    }
</script>
