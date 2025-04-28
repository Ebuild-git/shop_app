<form wire:submit="update">

    @include('components.alert-livewire')

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>{{ __('current_password') }}</label>
                <input type="text" class="form-control border-r shadow-none" @error('old_password') is-invalid @enderror
                    wire:model="old_password">
                @error('old_password')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-6">
            <div>
                <label>{{ __('new_password') }}</label>
                <div class="form-group" style="position: relative;">
                    <input type="password" class="form-control border-r shadow-none" id="password-1"
                        @error('password') is-invalid @enderror wire:model="password">
                    <button class="password_show" type="button" onclick="showPassword(1)" style="{{ App::isLocale('ar') ? 'left: 0; right: auto;' : 'right: 0; left: auto;' }} position: absolute; top: 22%; transform: translateY(-50%);">
                        <span class="input-group-text">
                            <i class="bi bi-eye"></i>
                        </span>
                    </button>
                </div>
                @error('password')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-6">
            <div >
                <label>{{ __('confirm_password') }}</label>
                <div class="form-group" style="position: relative;">
                    <input type="password" class="form-control border-r shadow-none" id="password-2"
                        @error('password_confirmation') is-invalid @enderror wire:model="password_confirmation"
                        >
                    <button class="password_show" type="button" onclick="showPassword(2)" style="{{ App::isLocale('ar') ? 'left: 0; right: auto;' : 'right: 0; left: auto;' }} position: absolute; top: 22%; transform: translateY(-50%);">
                        <span class="input-group-text">
                            <i class="bi bi-eye"></i>
                        </span>
                    </button>
                </div>
                @error('password_confirmation')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>
    <div class="modal-footer mt-3">
        <button type="submit" class="bg">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading></span>
            {{ __('update_password') }}
            <i class="bi bi-arrow-right-circle-fill"></i>
        </button>
    </div>
</form>
