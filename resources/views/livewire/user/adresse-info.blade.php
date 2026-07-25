<div>
    <div class="address-wrapper" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
        <div class="address-card">
            <div class="address-card__header">
                <div class="address-card__header-left">
                    <span class="address-card__icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </span>
                    <h5 class="address-card__title mb-0">{{ __('current_address') }}</h5>
                </div>

                <a href="{{ url('informations?section=perso') }}" class="address-card__edit-btn" title="{{ __('edit') }}">
                    <i class="bi bi-pencil-square"></i>
                    <span class="d-none d-sm-inline">{{ __('update') }}</span>
                </a>
            </div>

            <div class="address-card__body">
                <div class="address-card__row">
                    <i class="bi bi-person-fill address-card__row-icon"></i>
                    <span class="address-card__name">
                        {{ $user->gender == 'male' ? __('gender_male') : __('gender_female') }}
                        {{ ucfirst($user->firstname) }} {{ ucfirst($user->lastname) }}
                    </span>
                </div>

                <div class="address-card__row">
                    <i class="bi bi-house-door-fill address-card__row-icon"></i>
                    <span>
                        @if ($user->city_id && $user->rue && $user->nom_batiment && $user->region_info)
                            {!! $user->num_appartement ? 'App. ' . $user->num_appartement . ', ' : '' !!}
                            {!! ($user->etage !== null && $user->etage !== '') ? 'Étage ' . $user->etage . ', ' : '' !!}
                            {!! $user->nom_batiment ? 'Résidence ' . $user->nom_batiment . ', ' : '' !!}
                            {!! $user->rue ? 'Rue ' . $user->rue . ', ' : '' !!}
                            {!! optional($user->city)->name ? 'Ville ' . $user->city->name . ', ' : '' !!}
                            {!! optional($user->region_info)->nom ? $user->region_info->nom : '' !!}
                        @else
                            <span class="text-muted">{{ __('incomplete_address') }}</span>
                        @endif
                    </span>
                </div>

                <div class="address-card__row mb-0">
                    <i class="bi bi-telephone-fill address-card__row-icon"></i>
                    <span>{{ $user->phone_number ?: __('incomplete_address') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
