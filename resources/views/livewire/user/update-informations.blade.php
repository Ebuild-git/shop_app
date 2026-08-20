<form wire:submit="update">
    @include('components.alert-livewire')
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>{{ __('profile_photo') }}</label>
                <div class="custom-file" style="{{ app()->getLocale() == 'ar' ? 'text-align: left; direction: ltr;' : 'text-align: left; direction: ltr;' }}">
                    <input type="file" class="custom-file-input" wire:model="avatar" id="avatar" accept="image/*">
                    <label class="custom-file-label" for="avatar">{{ __('choose_file') }}</label>
                </div>

                @if ($avatar)
                    <div class="mt-2">
                        <img src="{{ $avatar->temporaryUrl() }}" alt="Avatar preview" style="width: 100px; height: 100px;">
                    </div>
                @elseif(Auth::user()->avatar)
                    <div class="mt-2">
                        @if (Auth::user()->avatar == 'avatar.png')
                            <img src="https://t3.ftcdn.net/jpg/05/00/54/28/360_F_500542898_LpYSy4RGAi95aDim3TLtSgCNUxNlOlcM.jpg" alt="Default Avatar" style="width: 100px; height: 100px;">
                        @else
                            <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="Current Avatar" style="width: 100px; height: 100px;">
                        @endif
                    </div>
                @else
                    <div class="mt-2">
                        <img src="https://t3.ftcdn.net/jpg/05/00/54/28/360_F_500542898_LpYSy4RGAi95aDim3TLtSgCNUxNlOlcM.jpg" alt="Default Avatar" style="width: 100px; height: 100px;">
                    </div>
                @endif

                @error('avatar')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="col-sm-4">*
            <label>{{ __('nom') }}</label>
            <span class="text-danger">*</span>
            <div class="form-group">
                <input type="text" class="form-control border-r shadow-none" value="{{ Auth::user()->lastname }}" readonly>
            </div>
        </div>
        <div class="col-sm-4">
            <label>{{ __('prenom') }}</label>
            <span class="text-danger">*</span>
            <div class="form-group">
                <input type="text" class="form-control border-r shadow-none" value="{{ Auth::user()->firstname }}" readonly>
            </div>
        </div>
        <div class="col-sm-4">
            <label>{{ __('pseudonyme') }} </label>
                <span class="text-danger">*</span>
            <div class="form-group">
                <input type="text" class="form-control border-r shadow-none" value="{{ Auth::user()->username }}" readonly>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>{{ __('email') }}</label>
                <span class="text-danger">*</span>
                <input type="email" class="form-control border-r shadow-none" @error('email') is-invalid @enderror
                    wire:model="email">
                @error('email')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>{{ __('telephone') }}</label>
                <span class="text-danger">*</span>
                <input type="tel" class="form-control border-r shadow-none" oninput="formatTelephone(this)" maxlength="14"
                    @error('phone_number') is-invalid @enderror wire:model="phone_number">
                @error('phone_number')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4 mt-1">
            <div class="form-group">
                <span for="small">{{ __('date_naissance') }}</span>
                <span class="text-danger">*</span>
                <div class="input-group">
                    <select wire:model="jour" class="form-control">
                        <option selected disabled>Jour</option>
                        @for ($i = 1; $i <= 31; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    <select wire:model="mois" class="form-control">
                        <option selected disabled>Mois</option>
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}">
                                {{ strftime('%B', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endforeach
                    </select>
                    <select wire:model="annee" class="form-control">
                        <option selected disabled>Année</option>
                        @for ($year = date('Y'); $year >= date('Y') - 100; $year--)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>
                </div>

                @error('jour')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
                @error('mois')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
                @error('annee')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        {{-- <div class="col-sm-4">
            <div class="form-group">
                <label>{{ __('Région') }}</label>
                <span class="text-danger">*</span>
                <select class="form-control border-r shadow-none" wire:model="region" >
                    <option value=""></option>
                    @foreach ($regions as $item)
                        <option value="{{ $item->id }}">{{ $item->nom }}</option>
                    @endforeach
                </select>
                @error('region')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div> --}}

        <div class="col-sm-4">
            <div class="form-group">
                <label>{{ __('Région') }}</label>
                <span class="text-danger">*</span>
                <select class="form-control border-r shadow-none" id="region-select" wire:model="region">
                    <option value="">{{ __('Sélectionner') }}</option>
                    @foreach ($regions as $item)
                        <option value="{{ $item->id }}" @selected($item->id == $region)>{{ $item->nom }}</option>
                    @endforeach
                </select>
                @error('region')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        {{-- <div class="col-sm-4">
            <div class="form-group">
                <label>{{ __('ville') }}</label>
                <div wire:ignore>
                    <select class="form-control border-r shadow-none" id="city-select">
                        <option value="">{{ __('select_city') }}</option>
                        @foreach(\App\Models\City::all() as $city)
                            <option value="{{ $city->id }}" @selected($city->id == $city_id)>{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('city_id')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div> --}}
        <div class="col-sm-4">
            <div class="form-group">
                <label>{{ __('ville') }}</label>
                <div wire:ignore>
                    <select class="form-control border-r shadow-none" id="city-select">
                        {{-- populated by JS, filtered by selected region --}}
                    </select>
                </div>
                @error('city_id')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>{{ __('rue') }}</label>
                <span class="text-danger">*</span>
                <input type="text" class="form-control border-r shadow-none"
                    wire:model="rue">
                @error('rue')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>{{ __('batiment') }}</label>
                <span class="text-danger">*</span>
                <input type="text" class="form-control border-r shadow-none"
                    wire:model="nom_batiment">
                @error('nom_batiment')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label>{{ __('etage') }}</label>
                <input type="text" class="form-control border-r shadow-none"
                    wire:model="etage">
                @error('etage')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label>{{ __('num_appartement') }}</label>
                <input type="text" class="form-control border-r shadow-none"
                    wire:model="num_appartement">
                @error('num_appartement')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

    </div>
    <br>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" onclick="confirmDeleteAccount({{ Auth::id() }}, @this)">
            {{ __('delete_account') }}
        </button>

        {{-- <button type="submit" class="bg">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                wire:loading></span>
                {{ __('save_changes2') }}
            <i class="bi bi-arrow-right-circle-fill"></i>
        </button> --}}
        <button type="submit" class="bg">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading></span>
            {{ $this->isFirstTime ? __('save_changes2') : __('update') }}
            <i class="bi bi-arrow-right-circle-fill"></i>
        </button>
    </div>
</form>
<script>
    window.translations_swal = {
        confirm_title: "{{ __('confirm_title') }}",
        confirm_text: "{{ __('confirm_text') }}",
        confirm_button: "{{ __('confirm_button') }}",
        cancel_button: "{{ __('cancel_button') }}",
        deleted_title: "{{ __('deleted_title') }}",
        deleted_text: "{{ __('deleted_text') }}"
    };
</script>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('scroll-to-first-error', () => {
            setTimeout(() => {
                const firstError = document.querySelector('.text-danger');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }, 50);
        });
    });
</script>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('redirect-home', () => {
            setTimeout(() => {
                window.location.href = "{{ route('home') }}";
            }, 2500);
        });

        Livewire.on('redirect-back', (event) => {
            const target = Array.isArray(event) ? event[0]?.url : event.url;
            setTimeout(() => {
                window.location.href = target;
            }, 2500);
        });
    });
</script>
@php
    // Built here rather than inline inside @json() to avoid a Blade
    // directive-parsing issue with multi-line arrow functions + arrays.
    $citiesForJs = $cities->map(function ($c) {
        return [
            'value' => (string) $c->id,
            'label' => $c->name,
            'region_id' => (string) $c->region_id,
        ];
    });
@endphp

<script>
    window.allCities = @json($citiesForJs);

    let citySelectChoices = null;

    function setCityPlaceholder(text) {
        if (!citySelectChoices) return;
        citySelectChoices.clearStore();
        citySelectChoices.setChoices(
            [{ value: '', label: text, disabled: true, selected: true }],
            'value', 'label', true
        );
    }

    function loadCitiesForRegion(regionId, selectedCityId) {
        const cityEl = document.getElementById('city-select');
        if (!citySelectChoices || !cityEl) return;

        const filtered = window.allCities.filter(c => c.region_id === String(regionId));

        citySelectChoices.clearStore();
        citySelectChoices.setChoices(
            [
                { value: '', label: @json(__('select_city')), disabled: true, selected: !selectedCityId },
                ...filtered,
            ],
            'value', 'label', true
        );
        cityEl.disabled = filtered.length === 0;

        if (selectedCityId) {
            citySelectChoices.setChoiceByValue(String(selectedCityId));
        }
    }

    function initCitySelect() {
        const el = document.getElementById('city-select');
        if (!el || el.closest('.choices')) return; // already initialized

        citySelectChoices = new Choices(el, {
            searchEnabled: true,
            searchPlaceholderValue: @json(__('type_to_search_city')),
            noResultsText: @json(__('no_matching_city')),
            noChoicesText: @json(__('select_region_first')),
            itemSelectText: '',
            shouldSort: false,
            placeholder: true,
            allowHTML: false,
        });

        el.addEventListener('change', function () {
            @this.set('city_id', el.value);
        });

        const regionEl = document.getElementById('region-select');
        const initialRegion = (regionEl && regionEl.value) ? regionEl.value : @json((string) $region);

        if (initialRegion) {
            loadCitiesForRegion(initialRegion, @json((string) $city_id));
        } else {
            el.disabled = true;
            setCityPlaceholder(@json(__('select_region_first')));
        }
    }

    // Delegated listener: keeps working even if Livewire replaces the
    // region select on a re-render triggered by something else (e.g. avatar upload).
    document.addEventListener('change', function (e) {
        if (e.target && e.target.id === 'region-select') {
            if (e.target.value) {
                loadCitiesForRegion(e.target.value, null);
            } else if (citySelectChoices) {
                document.getElementById('city-select').disabled = true;
                setCityPlaceholder(@json(__('select_region_first')));
            }
        }
    });

    document.addEventListener('livewire:initialized', () => {
        initCitySelect();
    });

    document.addEventListener('livewire:navigated', () => {
        initCitySelect();
    });
</script>
