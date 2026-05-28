@extends('User.fixe')
@section('titre', 'Shopiners Bloqués')
@section('content')
@section('body')

<style>
    .blocked-page-wrap {
        min-height: 70vh;
        background: #f7f8fa;
        padding: 48px 0 64px;
    }

    .blocked-page-header {
        margin-bottom: 36px;
    }

    .blocked-page-header h1 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #111827;
        letter-spacing: -0.02em;
        margin: 0 0 4px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .blocked-page-header h1 .header-icon {
        width: 38px;
        height: 38px;
        background: #fee2e2;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #ef4444;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .blocked-page-header p {
        font-size: 0.85rem;
        color: #9ca3af;
        margin: 0;
        padding-left: 48px;
    }

    .blocked-count-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 0.78rem;
        font-weight: 600;
        color: #ef4444;
        margin-left: 10px;
        vertical-align: middle;
    }

    /* ── User cards ── */
    .blocked-grid {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .blocked-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        transition: box-shadow 0.18s, transform 0.18s;
        animation: cardIn 0.3s ease both;
    }

    .blocked-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.07);
        transform: translateY(-1px);
    }

    @keyframes cardIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .blocked-card:nth-child(1)  { animation-delay: 0.03s; }
    .blocked-card:nth-child(2)  { animation-delay: 0.06s; }
    .blocked-card:nth-child(3)  { animation-delay: 0.09s; }
    .blocked-card:nth-child(4)  { animation-delay: 0.12s; }
    .blocked-card:nth-child(5)  { animation-delay: 0.15s; }
    .blocked-card:nth-child(6)  { animation-delay: 0.18s; }

    .blocked-card-left {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }

    .blocked-avatar-wrap {
        position: relative;
        flex-shrink: 0;
    }

    .blocked-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #f1f5f9;
    }

    .blocked-badge-dot {
        position: absolute;
        bottom: 1px;
        right: 1px;
        width: 13px;
        height: 13px;
        background: #ef4444;
        border-radius: 50%;
        border: 2px solid #fff;
    }

    .blocked-username {
        font-weight: 700;
        font-size: 0.92rem;
        color: #111827;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .blocked-fullname {
        font-size: 0.78rem;
        color: #9ca3af;
        margin-top: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ── Unblock button ── */
    .btn-do-unblock {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 22px;
        border: 1.5px solid #d1fae5;
        background: #f0fdf4;
        color: #16a34a;
        cursor: pointer;
        white-space: nowrap;
        flex-shrink: 0;
        transition: all 0.18s ease;
        letter-spacing: 0.01em;
    }

    .btn-do-unblock:hover {
        background: #16a34a;
        border-color: #16a34a;
        color: #fff;
        box-shadow: 0 4px 14px rgba(22,163,74,0.25);
    }

    /* ── Empty state ── */
    .blocked-empty-wrap {
        text-align: center;
        padding: 80px 20px;
        background: #fff;
        border-radius: 20px;
        border: 1px solid #f1f5f9;
    }

    .blocked-empty-emoji {
        font-size: 3.5rem;
        margin-bottom: 16px;
        display: block;
    }

    .blocked-empty-title {
        font-weight: 800;
        font-size: 1.1rem;
        color: #111827;
        margin-bottom: 6px;
    }

    .blocked-empty-sub {
        font-size: 0.85rem;
        color: #9ca3af;
        margin-bottom: 24px;
    }

    .btn-back-shopiners {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 22px;
        font-size: 0.83rem;
        font-weight: 600;
        border-radius: 22px;
        border: none;
        background: #111827;
        color: #fff;
        text-decoration: none;
        transition: background 0.18s;
    }

    .btn-back-shopiners:hover {
        background: #374151;
        color: #fff;
    }
</style>

<!-- Breadcrumb -->
<div class="gray py-3" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="/" aria-label="{{ __('home') }}"><i class="fas fa-home"></i></a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('shopiners') }}">{{ __('Shopiners') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ __('Shopiners Bloqués') }}
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Main content -->
<div class="blocked-page-wrap" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
    <div class="container" style="max-width: 640px;">

        <div class="blocked-page-header">
            <h1>
                <span class="header-icon"><i class="bi bi-slash-circle"></i></span>
                {!! \App\Traits\TranslateTrait::TranslateText('Shopiners Bloqués') !!}
                @if(!$blockedUsers->isEmpty())
                    <span class="blocked-count-badge">
                        <i class="bi bi-person-fill"></i>
                        {{ $blockedUsers->count() }}
                    </span>
                @endif
            </h1>
            <p>{{ __('Les utilisateurs que vous avez bloqués n\'apparaissent pas dans vos recherches.') }}</p>
        </div>

        @if($blockedUsers->isEmpty())
            <div class="blocked-empty-wrap">
                <span class="blocked-empty-emoji">😊</span>
                <div class="blocked-empty-title">
                    {{ __('Aucun shopiner bloqué') }}
                </div>
                <div class="blocked-empty-sub">
                    {{ __('Vous n\'avez bloqué personne pour l\'instant.') }}
                </div>
                <a href="{{ route('shopiners') }}" class="btn-back-shopiners">
                    <i class="bi bi-arrow-left"></i>
                    {{ __('Retour aux shopiners') }}
                </a>
            </div>
        @else
            <div class="blocked-grid">
                @foreach($blockedUsers as $blocked)
                    <div class="blocked-card">
                        <div class="blocked-card-left">
                            <div class="blocked-avatar-wrap">
                                @if ($blocked->avatar && $blocked->avatar != 'avatar.png' && $blocked->photo_verified_at)
                                    <img src="{{ Storage::url($blocked->avatar) }}" alt="Avatar" class="blocked-avatar">
                                @else
                                    <img src="https://t3.ftcdn.net/jpg/05/00/54/28/360_F_500542898_LpYSy4RGAi95aDim3TLtSgCNUxNlOlcM.jpg"
                                        alt="Default Avatar" class="blocked-avatar">
                                @endif
                                <span class="blocked-badge-dot"></span>
                            </div>
                            <div style="min-width:0;">
                                <a href="/user/{{ $blocked->id }}" style="text-decoration:none;">
                                    <div class="blocked-username">{{ $blocked->username }}</div>
                                </a>
                                <div class="blocked-fullname">{{ $blocked->firstname }} {{ $blocked->lastname }}</div>
                            </div>
                        </div>

                        <button class="btn-do-unblock" onclick="unblockUser({{ $blocked->id }}, '{{ addslashes($blocked->username) }}')">
                            <i class="bi bi-unlock"></i>
                            {!! \App\Traits\TranslateTrait::TranslateText('Débloquer') !!}
                        </button>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function unblockUser(userId, username) {
        Swal.fire({
            title: '{{ __("Débloquer ce shopiner ?") }}',
            html: `<span style="color:#6b7280;font-size:0.9rem;">{{ __("Vous verrez à nouveau les publications de") }} <b>${username}</b>.</span>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: '<i class="bi bi-unlock me-1"></i> {{ __("Oui, débloquer") }}',
            cancelButtonText: '{{ __("Annuler") }}',
            borderRadius: '16px',
            customClass: {
                popup: 'rounded-4',
            }
        }).then((result) => {
            if (!result.isConfirmed) return;

            fetch(`/user/${userId}/block`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => r.ok ? r.json() : r.json().then(e => { throw e; }))
            .then(() => {
                Swal.fire({
                    icon: 'success',
                    title: '{{ __("Utilisateur débloqué") }}',
                    html: `<span style="font-size:0.88rem;color:#6b7280;">{{ __("Vous verrez à nouveau ses publications.") }}</span>`,
                    showConfirmButton: false,
                    timer: 1400,
                    timerProgressBar: true,
                }).then(() => window.location.reload());
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __("Erreur") }}',
                    text: err.message || '{{ __("Une erreur est survenue.") }}',
                });
            });
        });
    }
</script>

@endsection
