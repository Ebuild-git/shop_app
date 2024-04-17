
<a href="{{ route('user-notifications') }}" style="color: black !important;">
    <i class="lni bi bi-bell icon-icon-header"></i>
    <span class="dn-counter bg-success-ps"> {{ $notifications->count() }}</span>
    <span class="hide-desktop">Notifications</span>
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

