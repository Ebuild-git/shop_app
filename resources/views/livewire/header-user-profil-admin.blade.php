<div
    class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
    <div class="user-profile-info">
       <span style="position: absolute;right: 10px;" wire:loading>
        <x-loading></x-loading>
       </span>
        <h4>
            {{ $user->firstname }} {{ $user->lastname }}
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

    @if (is_null($user->photo_verified_at))
        <a href="javascript:void(0)">
            <button class="btn btn-success" wire:click="photo()">
                <i class="ti ti-camera me-1"></i> Accepter 
            </button>
        </a>
    @else
        <a href="javascript:void(0)">
            <button class="btn btn-danger" wire:click="photo()">
                <i class="ti ti-camera me-1"></i> Réfuser
            </button>
        </a>
    @endif
</div>
