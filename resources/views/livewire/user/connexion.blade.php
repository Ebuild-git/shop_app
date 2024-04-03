<div>
    <form wire:submit.prevent="connexion">

        @include('components.alert-livewire')

        <div class="form-group">
            <label>Adresse E-mail</label>
            <input type="email" name="email" id="email"
                class="form-control  @error('email') is-invalid @enderror form-control-ps shadow-none"
                wire:model.lazy="email" placeholder="Email / Nom d'utilisateur">
            @error('email')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group ">
            <label>Mot de passe</label>
            <div class="position-relative">
                <img width="30" height="30" src="/icons/visible.png" class="image-visible-login show"
                    alt="." id="show" />
                <input type="password" name="password" id="password"
                    class="form-control  @error('password') is-invalid @enderror form-control-ps shadow-none"
                    wire:model.lazy="password" placeholder="*****">
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
                <a href="/inscription" class="link">Créer un compte</a>
            </div>
            <div>
                <span wire:loading>
                    <x-Loading></x-Loading>
                </span>
            </div>
            <div>
                <button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium">

                    Connexion
                    <i class="bi bi-arrow-right-circle-fill"></i>
                </button>
            </div>
        </div>
        <script>
            //change password type when i click on see button
            $("#show").on('click', function() {
                $('#password').prop('type', 'text');
            });
        </script>
        </script>
</div>
