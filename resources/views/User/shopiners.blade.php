@extends('User.fixe')
@section('titre', 'Shopiners')
@section('content')
@section('body')

<div class="gray py-3" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/" aria-label="{{ __('home') }}"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('shopiners') }}">{{ __('Shopiners') }}</a>
                </li>
            </ol>
        </nav>
    </div>
</div>

<div class="container pt-4 pb-5">

    {{-- Tab Nav --}}
    @auth
    <div class="shopiner-tabs-wrap">
        <button class="shopiner-tab active" id="tab-all" onclick="switchTab('all')">
            <i class="bi bi-people"></i>
            {{ __('Shopiners')}}
        </button>
        <button class="shopiner-tab" id="tab-blocked" onclick="switchTab('blocked')">
            <i class="bi bi-slash-circle"></i>
            {{ __('Shopiners Bloqués') }}
            @php $blockedCount = count(auth()->user()->blockedUserIds()); @endphp
            @if($blockedCount > 0)
                <span class="tab-blocked-count">{{ $blockedCount }}</span>
            @endif
        </button>
    </div>
    @endauth

    {{-- Tab: All Shopiners --}}
    <div id="panel-all">
        @livewire('User.Shopinners')
    </div>

    {{-- Tab: Blocked Shopiners --}}
    @auth
    <div id="panel-blocked" style="display:none;">

        @php
            $blockedUsers = \App\Models\User::whereIn('id', auth()->user()->blockedUserIds())->get();
        @endphp

        <div style="max-width: 640px; margin: 0 auto;">

            <div class="blocked-page-header">
                <h1>
                    <span class="header-icon"><i class="bi bi-slash-circle"></i></span>
                    {{ __('Shopiners Bloqués')}}
                    @if($blockedUsers->isNotEmpty())
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
                </div>
            @else
                <div class="blocked-grid" id="blocked-grid">
                    @foreach($blockedUsers as $blocked)
                        <div class="blocked-card" id="blocked-card-{{ $blocked->id }}">
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
                                {{ __('Débloquer') }}
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
    @endauth

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function switchTab(tab) {
        document.getElementById('panel-all').style.display     = tab === 'all'     ? 'block' : 'none';
        document.getElementById('panel-blocked').style.display = tab === 'blocked' ? 'block' : 'none';
        document.getElementById('tab-all').classList.toggle('active',     tab === 'all');
        document.getElementById('tab-blocked').classList.toggle('active', tab === 'blocked');

        // persist across reload
        sessionStorage.setItem('shopinerTab', tab);
    }

    // Restore active tab on page load
    document.addEventListener('DOMContentLoaded', () => {
        const saved = sessionStorage.getItem('shopinerTab');
        if (saved === 'blocked') switchTab('blocked');
    });

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
            customClass: { popup: 'rounded-4' }
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
                // Remove card from DOM without full reload
                const card = document.getElementById(`blocked-card-${userId}`);
                if (card) {
                    card.style.transition = 'opacity 0.3s, transform 0.3s';
                    card.style.opacity = '0';
                    card.style.transform = 'translateX(20px)';
                    setTimeout(() => card.remove(), 300);
                }

                // Update badge count
                const badge = document.querySelector('.tab-blocked-count');
                if (badge) {
                    const current = parseInt(badge.textContent);
                    if (current - 1 <= 0) badge.remove();
                    else badge.textContent = current - 1;
                }

                Swal.fire({
                    icon: 'success',
                    title: '{{ __("Utilisateur débloqué") }}',
                    html: `<span style="font-size:0.88rem;color:#6b7280;">{{ __("Vous verrez à nouveau ses publications.") }}</span>`,
                    showConfirmButton: false,
                    timer: 1400,
                    timerProgressBar: true,
                });
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

<style>
    /* ── Tabs ── */
    .shopiner-tabs-wrap {
        display: flex;
        gap: 6px;
        margin-bottom: 28px;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 0;
    }

    .shopiner-tab {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 20px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #9ca3af;
        background: transparent;
        border: none;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        cursor: pointer;
        border-radius: 0;
        transition: color 0.18s, border-color 0.18s;
        letter-spacing: 0.01em;
    }

    .shopiner-tab:hover {
        color: #374151;
    }

    .shopiner-tab.active {
        color: #111827;
        border-bottom-color: #111827;
    }

    .tab-blocked-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 20px;
        height: 20px;
        padding: 0 6px;
        background: #fee2e2;
        color: #ef4444;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
    }

    /* ── Blocked panel styles (carried from blocked_shopiners view) ── */
    .blocked-page-header { margin-bottom: 28px; }
    .blocked-page-header h1 {
        font-size: 1.4rem;
        font-weight: 800;
        color: #111827;
        letter-spacing: -0.02em;
        margin: 0 0 4px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .blocked-page-header h1 .header-icon {
        width: 38px; height: 38px;
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
        font-size: 0.83rem;
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
        margin-left: 8px;
    }
    .blocked-grid { display: flex; flex-direction: column; gap: 10px; }
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
    .blocked-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.07); transform: translateY(-1px); }
    @keyframes cardIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .blocked-card-left { display: flex; align-items: center; gap: 14px; min-width: 0; }
    .blocked-avatar-wrap { position: relative; flex-shrink: 0; }
    .blocked-avatar { width: 52px; height: 52px; border-radius: 50%; object-fit: cover; border: 2px solid #f1f5f9; }
    .blocked-badge-dot {
        position: absolute; bottom: 1px; right: 1px;
        width: 13px; height: 13px;
        background: #ef4444; border-radius: 50%; border: 2px solid #fff;
    }
    .blocked-username { font-weight: 700; font-size: 0.92rem; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .blocked-fullname { font-size: 0.78rem; color: #9ca3af; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .btn-do-unblock {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 18px; font-size: 0.8rem; font-weight: 600;
        border-radius: 22px; border: 1.5px solid #d1fae5;
        background: #f0fdf4; color: #16a34a;
        cursor: pointer; white-space: nowrap; flex-shrink: 0;
        transition: all 0.18s ease;
    }
    .btn-do-unblock:hover { background: #16a34a; border-color: #16a34a; color: #fff; box-shadow: 0 4px 14px rgba(22,163,74,0.25); }
    .blocked-empty-wrap { text-align: center; padding: 80px 20px; background: #fff; border-radius: 20px; border: 1px solid #f1f5f9; }
    .blocked-empty-emoji { font-size: 3.5rem; margin-bottom: 16px; display: block; }
    .blocked-empty-title { font-weight: 800; font-size: 1.1rem; color: #111827; margin-bottom: 6px; }
    .blocked-empty-sub { font-size: 0.85rem; color: #9ca3af; }

    /* ── existing custom-select ── */
    .custom-select {
        background-color: #fff; border: 1px solid #ced4da; border-radius: 0.25rem;
        padding: 0.375rem 1.75rem 0.375rem 0.75rem; font-size: 1rem; line-height: 1.5;
        color: #495057; appearance: none; transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .custom-select:hover, .custom-select:focus {
        border-color: #80bdff; outline: 0; box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    }
</style>
