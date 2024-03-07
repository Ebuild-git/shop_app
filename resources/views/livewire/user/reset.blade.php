<div>
    @if (session()->has('error'))
        <div class="alert alert-danger small text-center">
            {{ session('error') }}
        </div>
        <br>
    @enderror
    @if (session()->has('info'))
        <div class="alert alert-info small text-center">
            {{ session('info') }}
        </div>
        <br>
    @enderror
    @if (session()->has('success'))
        <div class="alert alert-success small text-center">
            {{ session('success') }}
        </div>
        <br>
    @enderror


    <p class="text-center">
       Veuillez entrer votre nouveau mot de passe
    </p>
    <!-- Champ pour entrer l'email -->
    <form wire:submit="reset_password">
        <div class="form-group">
            <label for="email">Nouveau mot de passe</label>
            <input type="text" required
                class="form-control form-control-ps shadow-none @error('password') is-invalid @enderror" wire:model="password"
                placeholder="Entrez votre Mot de passe">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="email">Confirmation du mot de passe</label>
            <input type="text" required
                class="form-control form-control-ps shadow-none @error('password_confirmation') is-invalid @enderror" wire:model="password_confirmation"
                placeholder="Confirmation du mot de passe">
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
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

