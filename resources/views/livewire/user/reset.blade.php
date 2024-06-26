<div>
    @include('components.alert-livewire')

    <p class="text-center">
        Veuillez entrer votre nouveau mot de passe
    </p>
    <!-- Champ pour entrer l'email -->
    <form wire:submit="reset_password">
        <div class="form-group">
            <label for="email">Nouveau mot de passe</label>
            <div class="form-group" style="position: relative;">
                <input type="text" required id="password-1"
                    class="form-control form-control-ps shadow-none @error('password') is-invalid @enderror"
                    wire:model="password" placeholder="Entrez votre Mot de passe">
                <button class="password_show" type="button" onclick="showPassword(1)">
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
            <label for="email">Confirmation du mot de passe</label>
            <div class="form-group" style="position: relative;">
                <input type="text" required id="password-2"
                    class="form-control form-control-ps shadow-none @error('password_confirmation') is-invalid @enderror"
                    wire:model="password_confirmation" placeholder="Confirmation du mot de passe">
                <button class="password_show" type="button" onclick="showPassword(2)">
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
                <a href="{{ route('connexion') }}" id="openModal2" class="link">Connexion</a>
            </div>
            <div>
                <button type="submit" class="btn bg-red shadow-none" id="btn-submit">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                        wire:loading></span>
                    Réinitialiser
                    <i class="bi bi-arrow-right-circle-fill"></i>
                </button>
            </div>
        </div>
    </form>
</div>
