<a href="{{ route('user-notifications') }}" class="notification-header-icon">
    <i class="bi bi-bell"></i>
    <span class="small position-absolute">
        {{ $notifications->count() }}
    </span>
</a>

<script>
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('5b6f7ad6a8cf384098d9', {
        cluster: 'eu'
    });

    var channel = pusher.subscribe('my-channel-user-admin-{{ Auth::user()->id}}' );
    channel.bind('my-event', function(data) {
        window.Livewire.dispatch('notificationReceived');
    });
</script>
