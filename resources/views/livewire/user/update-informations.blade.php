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
                    wire:model="email" required>
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
                    @error('phone_number') is-invalid @enderror wire:model="phone_number" required>
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

        <div class="col-sm-4">
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
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>{{ __('ville') }}</label>
                <span class="text-danger">*</span>
                <input type="text" class="form-control border-r shadow-none"
                    wire:model="address">
                @error('address')
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

        <button type="submit" class="bg">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                wire:loading></span>
                {{ __('save_changes2') }}
            <i class="bi bi-arrow-right-circle-fill"></i>
        </button>
    </div>
</form>
<script>
    window.translations = {
        confirm_title: "{{ __('confirm_title') }}",
        confirm_text: "{{ __('confirm_text') }}",
        confirm_button: "{{ __('confirm_button') }}",
        cancel_button: "{{ __('cancel_button') }}",
        deleted_title: "{{ __('deleted_title') }}",
        deleted_text: "{{ __('deleted_text') }}"
    };
</script>

