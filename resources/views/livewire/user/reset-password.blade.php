<div>
    @include('components.alert-livewire')

    <p class="text-center">
        Veuillez entrer votre adresse mail lier a votre compte pour recevoir votre mot de passe temporaire
        de
        connexion.
    </p>
    <!-- Champ pour entrer l'email -->
    <form wire:submit="reset_password">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" required
                class="form-control shadow-none @error('email') is-invalid @enderror" wire:model="email"
                placeholder="Entrez votre email">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <br>
        <br>
        <div class="d-flex justify-content-between">
            <div>
                <a href="#" data-toggle="modal" data-target="#login" class="link">Connexion</a>
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

