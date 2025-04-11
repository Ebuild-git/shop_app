@extends('User.fixe')
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
                            <li class="breadcrumb-item">
                                <a href="/"><i class="fas fa-home"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('notifications')}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- ============================= Filter Wrap ============================== -->

    <div class="container pt-5 pb-5" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
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
                                            <h6 class="mb-0 mt-2">
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
                            <h6 class="text-center">Pas de Notifications</h6>
                            <span class="text-muted">
                                <i> Vous n'avez pas de notification actuellement.</i>
                            </span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection
<style>
/* Base styles for mobile */
@media (max-width: 767px) {
    .card .d-flex .text-end .small {
        font-size: 0.60rem; /* Smaller font size for time */
        margin-top: 10px;
    }

    .card .d-flex .text-end .bi-app-indicator,
    .card .d-flex .text-end .bi-trash3 {
        font-size: 0.855rem; /* Even smaller icon size */
        margin-top: 10px;
    }

    .card a {
        font-size: 0.855rem; /* Smaller font size for URL */
    }
}
</style>
