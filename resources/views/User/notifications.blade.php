{{-- @extends('User.fixe')
@section('titre', 'Mes notifications')
@section('content')
@section('body')
    <!-- ======================= Filter Wrap Style 1 ======================== -->
    <section class="gray py-3" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/" aria-label="{{ __('home') }}"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('notifications')}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- ============================= Filter Wrap ============================== -->

    <div class="container pt-5 pb-5" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
        <div class="row">
            <div class="col-sm-12">
                <div class="card p-2">
                    @if ($notifications->isNotEmpty())
                        <div class="text-end mb-2">
                            <button class="btn btn-sm" onclick="delete_all_notification()">
                                <i class="bi bi-x-lg text-danger"></i>
                                <span class="text-danger">{{ __('remove_all') }}</span>
                            </button>
                        </div>
                    @endif
                    @forelse ($notifications as $item)
                        <div class="card p-2 mb-1" id="tr-{{ $item->id }}">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-start w-100">
                                    <img width="45" height="45" src="/icons/alarm--v1.png" alt="Notification Icon" class="me-4" />
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-0 mt-2" style="{{ app()->getLocale() == 'ar' ? 'margin-right: 5px;' : '' }}">
                                                <b>
                                                    @if ($item->url)
                                                        <a href="{{ $item->url }}" class="link" style="margin-left: 10px;">
                                                    @else
                                                        <a href="#" class="link" style="margin-left: 10px;">
                                                    @endif
                                                    {{ $item->titre }}
                                                    </a>
                                                </b>
                                            </h6>
                                            <div class="text-end">
                                                <div class="small">
                                                    <i class="bi bi-app-indicator"></i>
                                                    {{ \Carbon\Carbon::parse($item->created_at)->locale('fr')->diffForHumans() }}
                                                    &nbsp;
                                                    <span onclick="delete_notification({{ $item->id }})" class="cursor-pointer">
                                                        <i class="bi bi-trash3 text-danger"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="mt-2" style="margin-left: 10px;">{!! $item->message !!}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="pb-5 pt-5 text-center">
                            <img src="https://static.vecteezy.com/system/resources/thumbnails/023/570/826/small/still-empty-no-notification-yet-concept-illustration-line-icon-design-eps10-modern-graphic-element-for-landing-page-empty-state-ui-infographic-vector.jpg"
                                alt="No Notifications" />
                            <h6 class="text-center">{{ __('no_notifications') }}</h6>
                            <span class="text-muted">
                                <i> {{ __('no_notifications_message') }}</i>
                            </span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection
 --}}
@extends('User.fixe')
@section('titre', 'Mes notifications')
@section('content')
@section('body')

    <section class="gray py-3" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/" aria-label="{{ __('home') }}"><i class="fas fa-home"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('notifications') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <div class="container pt-5 pb-5"
         style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
        <div class="row">
            <div class="col-sm-12">
                <div class="card p-2">

                    {{-- Delete all button (shown/hidden dynamically) --}}
                    <div class="text-end mb-2" id="delete-all-btn" style="display: none !important;">
                        <button class="btn btn-sm" onclick="deleteAllNotifications()">
                            <i class="bi bi-x-lg text-danger"></i>
                            <span class="text-danger">{{ __('remove_all') }}</span>
                        </button>
                    </div>

                    {{-- Notifications rendered by JS --}}
                    <div id="notifications-page-list">
                        <div class="pb-3 pt-3 text-center text-muted">
                            <i class="bi bi-arrow-repeat"></i> Chargement...
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        var userId  = {{ Auth::id() }};
        var pusher  = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
        });

        // ── Fetch & render ────────────────────────────────────────────
        function fetchAndRender() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '/web/notifications/user/' + userId, true);
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    renderPage(data.notifications || []);
                }
            };
            xhr.send();
        }

        function renderPage(notifications) {
            var container  = document.getElementById('notifications-page-list');
            var deleteBtn  = document.getElementById('delete-all-btn');

            if (!notifications || notifications.length === 0) {
                deleteBtn.style.setProperty('display', 'none', 'important');
                container.innerHTML =
                    '<div class="pb-5 pt-5 text-center">' +
                        '<img src="https://static.vecteezy.com/system/resources/thumbnails/023/570/826/small/still-empty-no-notification-yet-concept-illustration-line-icon-design-eps10-modern-graphic-element-for-landing-page-empty-state-ui-infographic-vector.jpg" alt="No Notifications" />' +
                        '<h6 class="text-center">{{ __("no_notifications") }}</h6>' +
                        '<span class="text-muted"><i>{{ __("no_notifications_message") }}</i></span>' +
                    '</div>';
                return;
            }

            deleteBtn.style.setProperty('display', 'block', 'important');

            var html = '';
            notifications.forEach(function (item) {
                var timeAgo = item.time_ago || '';
                var link    = item.url ? item.url : '#';
                html +=
                    '<div class="card p-2 mb-1" id="tr-' + item.id + '">' +
                        '<div class="d-flex justify-content-between">' +
                            '<div class="d-flex align-items-start w-100">' +
                                '<img width="45" height="45" src="/icons/alarm--v1.png" alt="Notification Icon" class="me-4" />' +
                                '<div class="flex-grow-1">' +
                                    '<div class="d-flex justify-content-between">' +
                                        '<h6 class="mb-0 mt-2">' +
                                            '<b><a href="' + link + '" class="link" style="margin-left:10px;">' + item.titre + '</a></b>' +
                                        '</h6>' +
                                        '<div class="text-end">' +
                                            '<div class="small">' +
                                                '<i class="bi bi-app-indicator"></i> ' + timeAgo +
                                                ' &nbsp;' +
                                                '<span onclick="deleteSingle(' + item.id + ')" class="cursor-pointer">' +
                                                    '<i class="bi bi-trash3 text-danger"></i>' +
                                                '</span>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                    '<p class="mt-2" style="margin-left:10px;">' + item.message + '</p>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
            });

            container.innerHTML = html;
        }

        // ── Delete single ─────────────────────────────────────────────
        function deleteSingle(id) {
            var xhr = new XMLHttpRequest();
            xhr.open('DELETE', '/web/notifications/user/' + userId + '/' + id, true);
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var el = document.getElementById('tr-' + id);
                    if (el) el.remove();
                    // Check if list is now empty
                    var remaining = document.querySelectorAll('#notifications-page-list .card');
                    if (remaining.length === 0) renderPage([]);
                }
            };
            xhr.send();
        }

        // ── Delete all ────────────────────────────────────────────────
        function deleteAllNotifications() {
            var xhr = new XMLHttpRequest();
            xhr.open('DELETE', '/web/notifications/user/' + userId + '/all', true);
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            xhr.onload = function () {
                if (xhr.status === 200) renderPage([]);
            };
            xhr.send();
        }

        // ── Pusher: listen for new notifications ──────────────────────
        var channel = pusher.subscribe('my-channel-user-admin-' + userId);
        channel.bind('my-event', function (data) {
            console.log('New notification received:', data);
            fetchAndRender();
        });

        // ── Initial load ──────────────────────────────────────────────
        fetchAndRender();
    </script>

@endsection
