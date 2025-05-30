<div>

    @if ($send)
        <div class="alert alert-info">
            <img width="64" height="64" src="https://img.icons8.com/sf-black/64/008080/checked.png" alt="checked" />
            <h5>
                <b>{{ __('reset_link_sent_title') }}</b>
            </h5>
            <p class="text-muted">
                {{ __('reset_link_sent_message') }}
            </p>
            <br>
            <a href="{{ route('connexion') }}" class="btn btn-dark btn-block">
                {{ __('back_to_login') }}
            </a>
        </div>
    @else
        <!-- Champ pour entrer l'email -->
        <form wire:submit="reset_password">

            @include('components.alert-livewire')


            <p class="text-center alert alert-info">
                {{ __('reset_password_instruction') }}
            </p>



            <div class="form-group">
                <label for="email">{{ __('email1') }}</label>
                <input type="email" required class="form-control shadow-none @error('email') is-invalid @enderror"
                    wire:model="email" placeholder="{{ __('enter_your_email') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <br>
            <br>
            <div class="d-flex justify-content-between">
                <div class="my-auto">
                    <a href="{{ route('connexion') }}" id="#login" class="link">{{ __('connexion') }}</a>
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
    @endif



</div>
