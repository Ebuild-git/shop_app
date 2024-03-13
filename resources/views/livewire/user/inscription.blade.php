<form wire:submit="inscription">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @include('components.alert-livewire')


    <div class="div-1">
        <div class="form-group">
            <span for="small text-muted">Nom et prénom</span>
            <input type="text" placeholder="Nom et prénom"
                class="form-control @error('nom') is-invalid @enderror shadow-none" wire:model="nom" required>
            @error('nom')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <span for="small">Username</span>
                    <input type="tel"
                        class="form-control @error('username') is-invalid @enderror shadow-none"id="username"
                        placeholder="username23" wire:model="username" required>
                    @error('username')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <img src="/icons/maroc.webp" height="30" alt="" class="position-absolute"
                        style="bottom:30px;left: 30px;border-radius: 100%;">
                    <span for="small">Numéro de téléphone</span>
                    <input type="tel" style="padding-left: 50px;"
                        class="form-control @error('telephone') is-invalid @enderror shadow-none" id="telephone"
                        placeholder="Numéro de téléphone*" value="+212" wire:model="telephone" required>
                    @error('telephone')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <span for="small">Adresse email</span>
            <input type="email" class="form-control @error('email') is-invalid @enderror shadow-none" id="email"
                placeholder="Adresse email*" wire:model="email" required>
            @error('email')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <span for="small">Mot de passe</span>
            <div class="input-group mb-3">
                <input type="password" placeholder="Mot de passe" class="form-control  shadow-none" id="password"
                    wire:model="password" required>
                <div class="input-group-prepend text-red">
                    <span class="input-group-text" id="showPassword">
                        <i class="bi bi-eye"></i>
                    </span>
                </div>
            </div>
            @error('password')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <div class="fil-import-registrer">
                <input type="file" class="d-none" id="photo" wire:model="photo" required>
                <span id="select-image">
                    @if ($photo)
                        <img src="{{ $photo->temporaryUrl() }}" alt="" class="avatar-inscription">
                    @else
                        <img src="https://img.icons8.com/external-vectorslab-glyph-vectorslab/53/1A1A1A/external-Upload-business-office-supplies-vectorslab-glyph-vectorslab.png"
                            alt="" class="avatar-inscription">
                    @endif
                    <br>
                    <i>Veuillez selectionner une image de profil </i>
                </span>
            </div>
            @error('photo')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror

        </div>
        <div class="p-1">
            <input type="checkbox" id="shop"> je suis une boutique
        </div>

        @error('matricule')
            <small class="form-text text-danger">{{ $message }}</small>
        @enderror
        @if ($matricule)
            <div class="div-2">
            @else
                <div class="div-2 d-none">
        @endif

        <div class="form-group">
            <label for="">Matricule fiscal </label>
            <div class="fil-import-registrer">
                <input type="file" class="d-none" id="matricule" wire:model="matricule" required>
                <span id="select-matricule">
                    @if ($matricule)
                        <img width="48" height="48" src="https://img.icons8.com/fluency/48/ok--v1.png"
                            alt="ok--v1" class="avatar-inscription" />
                    @else
                        <img width="50" height="50" src="https://img.icons8.com/ios/50/1A1A1A/document--v1.png"
                            alt="document--v1" class="avatar-inscription" />
                    @endif
                    <br>
                    <i>Veuillez selectionner votre matricule </i>
                </span>
            </div>
        </div>
    </div>
    <br>

    </div>


    <div class="p-1">
        <input type="checkbox" id="accept"> j'ai lu
        <b>
            <i class="bi bi-link-45deg"></i>
            <a href="/conditions" target="__blank">Les Conditions générales</a>
        </b>
        et j'accepte !
    </div>



    <div class="d-flex justify-content-end">


        <div>
            <button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium" id="submit" disabled>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading></span>
                Terminer l'inscription
                <i class="bi bi-arrow-right-circle-fill"></i>
            </button>
        </div>
    </div>
</form>
<script>
    //change type password to text
    document.getElementById("showPassword").addEventListener("click", function() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    });

    // when user click in select-image simulate click in photo
    document.querySelector("#select-image").addEventListener('click', (event) => {
        event.preventDefault();
        document.querySelector('#photo').click();
    });


    // when user click in select-image simulate click in photo
    document.querySelector("#select-matricule").addEventListener('click', (event) => {
        event.preventDefault();
        document.querySelector('#matricule').click();
    });

    //show romove d-none class on div-2 if shop is checked
    let checkboxShop = document.getElementById('shop');
    checkboxShop.addEventListener('change', () => {
        if (checkboxShop.checked) {
            document.querySelector('.div-2').classList.remove('d-none');
        } else {
            document.querySelector('.div-2').classList.add('d-none');
        }
    });

    //enaeble submit button if accept is checked
    let checkboxAccept = document.getElementById('accept');
    checkboxAccept.addEventListener('change', () => {
        document.getElementById('submit').disabled = !checkboxAccept.checked;
    });
</script>
