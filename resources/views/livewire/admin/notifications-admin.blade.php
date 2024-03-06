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
            </div>
        </li>
        <li class="dropdown-notifications-list scrollable-container">
            <ul class="list-group list-group-flush">
                @forelse ($notifications as $item)
                    <li class="list-group-item list-group-item-action dropdown-notifications-item">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar">
                                    @if ($item->type == 'new_post')
                                        <img src="{{ Storage::url($item->getUser->avatar ?? '') }}" alt
                                            class="h-auto rounded-circle" />
                                    @else
                                        <img src="/assets-admin/img/avatars/1.png" alt class="h-auto rounded-circle" />
                                    @endif
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <a href="{{ $item->url }}?statut={{ $item->statut }}">
                                    <h6 class="mb-1">{{ $item->titre }}</h6>
                                </a>
                                <p class="mb-0">{{ $item->message }}</p>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                                </small>
                                
                            </div>
                            <div class="flex-shrink-0 dropdown-notifications-actions"
                                wire:click="delete({{ $item->id }} )">
                                <a href="javascript:void(0)" class="dropdown-notifications-read"><span
                                        class="badge badge-dot"></span></a>
                                <a href="javascript:void(0)" class="dropdown-notifications-archive"><span
                                        class="ti ti-x"></span>
                                </a>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item text-center text-primary fw-bold p-3">Aucune notification</li>
                    <audio src="{{ asset('/icons/ping-82822.mp3') }}"></audio>
                @endforelse

            </ul>
        </li>
        
        {{--  <li class="dropdown-menu-footer border-top">
            <a href="javascript:void(0);"
                class="dropdown-item d-flex justify-content-center text-primary p-2 h-px-40 mb-1 align-items-center">
                View all notifications
            </a>
        </li> --}}
    </ul>

</li>
<script>
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('5b6f7ad6a8cf384098d9', {
        cluster: 'eu'
    });

    var channel = pusher.subscribe('my-channel-private-admin');
    channel.bind('my-event', function(data) {
        window.Livewire.dispatch('notificationReceived');

    });
</script>

