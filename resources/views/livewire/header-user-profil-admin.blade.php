<div
    class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
    <div class="user-profile-info">
        <h4>
            {{ $user->name }}
            @if ($user->certifier == 'oui')
                <img width="20" height="20" src="https://img.icons8.com/sf-regular-filled/48/40C057/approval.png"
                    alt="approval" title="Certifié" />
            @endif
        </h4>
        <ul
            class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
            <li class="list-inline-item d-flex gap-1">
                <i class="ti ti-color-swatch"></i> {{ $user->email }}
            </li>
            <li class="list-inline-item d-flex gap-1"><i class="ti ti-map-pin"></i>
                {{ $user->ville ?? '/' }}</li>
            <li class="list-inline-item d-flex gap-1">
                <i class="ti ti-calendar"></i> Joined {{ $user->created_at }}
            </li>
        </ul>
    </div>
    @if ($user->certifier == 'oui')
        <a href="javascript:void(0)">
            <button class="btn btn-danger"  wire:click="decertifier()">
                <i class="ti ti-check me-1"></i> Décertifer
            </button>
        </a>
    @else
    <a href="javascript:void(0)">
        <button class="btn btn-success" wire:click="certifier()">
            <x-loading></x-loading>
            <i class="ti ti-check me-1"></i> Certifer
        </button>
    </a>
    @endif
</div>
