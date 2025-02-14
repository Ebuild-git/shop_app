<div>
    <form wire:submit.prevent="connexion">

        @include('components.alert-livewire')

        <div class="form-group">
            <label style="color: black;">Adresse E-mail / Pseudonyme</label>
            <input type="text" name="email" id="email-login" autocomplete="off"
                class="form-control  @error('email') is-invalid @enderror form-control-ps shadow-none" wire:model="email"
                placeholder="Email / Pseudonyme">
            @error('email')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group ">
            <label style="color: black;">Mot de passe</label>
            <div class="position-relative">
                <input type="{{ $showPassword ? 'text' : 'password' }}" name="password" id="password-login" autocomplete="off"
                    class="form-control  @error('password') is-invalid @enderror form-control-ps shadow-none"
                    wire:model="password" placeholder="*****">
                <button class="password_show2" type="button" wire:click="$toggle('showPassword')">
                    <span class="input-group-text">
                        <i class="bi bi-eye"></i>
                    </span>
                </button>
            </div>
            @error('password')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
            <div style="text-align: right;">
                <a href="/forget" class="link">Mot de passe oublié ?</a>
            </div>
        </div>
        <br>

        <br><br>
        <div class="d-flex justify-content-between">
            <div>
                <span style="color: black;">Vous n'avez pas encore de compte?</span>
                <a href="/inscription" class="link">S'inscrire</a>
            </div>
            <div>
                <span wire:loading>
                    <x-Loading></x-Loading>
                </span>
            </div>
            <div>
                <button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium" wire:loading.attr="disabled">
                    Se connecter
                    <i class="bi bi-arrow-right-circle-fill"></i>
                </button>
            </div>
        </div>

        <!-- Dans votre vue Livewire -->


</div>
