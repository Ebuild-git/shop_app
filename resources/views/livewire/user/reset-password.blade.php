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
        <div class="small">
            <input type="checkbox" id="checkbox">
            <b>Je m'engage a changer le mot de passe a ma premiere connexion pour ma sécurité.</b>
        </div>
        <br>
        <div class="d-flex justify-content-between">
            <div>
                <a href="{{ route('connexion') }}" id="openModal2" class="link">Connexion</a>
            </div>
            <div>
                <button type="submit" class="btn bg-red shadow-none" disabled id="btn-submit">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                        wire:loading></span>
                    Réinitialiser
                    <i class="bi bi-arrow-right-circle-fill"></i>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
   $("#checkbox").on("change", function() {
    if ($(this).is(":checked")) {
        $('#btn-submit').prop("disabled", false);
    } else {
        $('#btn-submit').prop("disabled", true);
    }
});

</script>
