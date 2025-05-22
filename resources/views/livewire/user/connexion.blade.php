<div>
    <form wire:submit.prevent="connexion">

        @include('components.alert-livewire')

        <div class="form-group">
            <label style="color: black;">{{ __('email_label') }}</label>
            <input type="text" name="email" id="email-login" autocomplete="off"
                class="form-control  @error('email') is-invalid @enderror form-control-ps shadow-none" wire:model="email"
                placeholder="{{ __('email_placeholder') }}">
            @error('email')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group ">
            <label style="color: black;">{{ __('password_label') }}</label>
            <div class="position-relative">
                <input type="{{ $showPassword ? 'text' : 'password' }}" name="password" id="password-login" autocomplete="off"
                    class="form-control  @error('password') is-invalid @enderror form-control-ps shadow-none"
                    wire:model="password" placeholder="*****">
                <button class="password_show2" type="button" wire:click="$toggle('showPassword')" style="{{ App::isLocale('ar') ? 'left: 0; right: auto;' : 'right: 0; left: auto;' }} position: absolute; top: 22%; transform: translateY(-50%);">
                    <span class="input-group-text">
                        <i class="bi bi-eye"></i>
                    </span>
                </button>
            </div>
            @error('password')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
            <div style="text-align: right;">
                <a href="/forget" class="link">{{ __('forgot_password') }}</a>
            </div>
        </div>
        <br>

        <br><br>
        <div class="d-flex justify-content-between">
            <div>
                <span style="color: black;">{{ __('no_account') }}</span>
                <a href="/inscription" class="link">{{ __('register') }}</a>
            </div>
            <div>
                <span wire:loading>
                    <x-Loading></x-Loading>
                </span>
            </div>
            <div>
                <button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium" wire:loading.attr="disabled">
                    {{ __('login') }}
                    <i class="bi bi-arrow-right-circle-fill"></i>
                </button>
            </div>
        </div>
</div>
