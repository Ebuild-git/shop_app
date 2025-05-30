<div>
    @include('components.alert-livewire')

    <p class="text-center">
        {{ __('enter_new_password') }}
    </p>
    <!-- Champ pour entrer l'email -->
    <form wire:submit="reset_password">
        <div class="form-group">
            <label for="email">{{ __('new_password') }}</label>
            <div class="form-group" style="position: relative;">
                <input type="text" required id="password-1"
                    class="form-control form-control-ps shadow-none @error('password') is-invalid @enderror"
                    wire:model="password" placeholder="{{ __('enter_password') }}">
                <button class="password_show" type="button" onclick="showPassword(1)" style="{{ App::isLocale('ar') ? 'left: 0; right: auto;' : 'right: 0; left: auto;' }} position: absolute; top: 20%; transform: translateY(-50%);">
                    <span class="input-group-text">
                        <i class="bi bi-eye"></i>
                    </span>
                </button>
            </div>
            @error('password')
                <div class="small text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="email">{{ __('confirm_password') }}</label>
            <div class="form-group" style="position: relative;">
                <input type="text" required id="password-2"
                    class="form-control form-control-ps shadow-none @error('password_confirmation') is-invalid @enderror"
                    wire:model="password_confirmation" placeholder="{{ __('confirm_password_placeholder') }}">
                <button class="password_show" type="button" onclick="showPassword(2)" style="{{ App::isLocale('ar') ? 'left: 0; right: auto;' : 'right: 0; left: auto;' }} position: absolute; top: 20%; transform: translateY(-50%);">
                    <span class="input-group-text">
                        <i class="bi bi-eye"></i>
                    </span>
                </button>
            </div>
            @error('password_confirmation')
                <div class="small text-danger">{{ $message }}</div>
            @enderror
        </div>
        <br>
        <br>
        <div class="d-flex justify-content-between">
            <div>
                <a href="{{ route('connexion') }}" id="openModal2" class="link">{{ __('connexion') }}</a>
            </div>
            <div>
                <button type="submit" class="btn bg-red shadow-none" id="btn-submit">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                        wire:loading></span>
                    {{ __('reset') }}
                    <i class="bi bi-arrow-right-circle-fill"></i>
                </button>
            </div>
        </div>
    </form>
</div>
