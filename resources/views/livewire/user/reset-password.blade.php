<div>

    @if ($send)
        <div class="alert alert-info">
            <img width="64" height="64" src="https://img.icons8.com/sf-black/64/008080/checked.png" alt="checked" />
            <h5>
                <b>Le Lien De Reinitialisation a été envoyé !</b>
            </h5>
            <p class="text-muted">
                Un email contenant le lien pour mettre à jour votre mot de passe a été envoyer.
                Veuillez vérifier votre boîte de réception.
            </p>
            <br>
            <a href="{{ route('connexion') }}" class="btn btn-dark btn-block">
                Retour à la page de connexion
            </a>
        </div>
    @else
        <!-- Champ pour entrer l'email -->
        <form wire:submit="reset_password">

            @include('components.alert-livewire')


            <p class="text-center alert alert-info">
                Veuillez entrer votre adresse mail lier à votre compte pour réinitialiser votre mot de passe
            </p>



            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" required class="form-control shadow-none @error('email') is-invalid @enderror"
                    wire:model="email" placeholder="Veuillez entrer votre email">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <br>
            <br>
            <div class="d-flex justify-content-between">
                <div class="my-auto">
                    <a href="{{ route('connexion') }}" id="#login" class="link">Connexion</a>
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
    @endif



</div>
