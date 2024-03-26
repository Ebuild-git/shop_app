<form wire:submit="inscription">

    @include('components.alert-livewire')


    <div class="div-1">
        <div class="form-group">
            <span for="small text-muted">Nom et prénom</span>
            <span class="text-danger">*</span>
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
                    <span class="text-danger">*</span>
                    <input type="tel"
                        class="form-control @error('username') is-invalid @enderror shadow-none"id="username"
                        placeholder="username23" wire:model.live="username" required>
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
                    <span class="text-danger">*</span>
                    <input type="tel" style="padding-left: 50px;"
                        class="form-control @error('telephone') is-invalid @enderror shadow-none" id="telephone"
                        placeholder="Numéro de téléphone*" value="+212" wire:model="telephone" required>
                    @error('telephone')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <span for="small">Genre</span>
                    <span class="text-danger">*</span>
                    <select wire:model="genre" class="form-control @error('genre') is-invalid @enderror shadow-none">
                        <option value=""></option>
                        <option value="Masculin">Masculin</option>
                        <option value="Féminin">Féminin</option>
                    </select>
                    @error('genre')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <span for="small">Date de naissance</span>
                    <span class="text-danger">*</span>
                    <input type="date" class="form-control @error('date') is-invalid @enderror shadow-none"
                        id="date" placeholder="date de naissance *" wire:model="date" required>
                    @error('date')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <span for="small">Adresse email</span>
                    <span class="text-danger">*</span>
                    <input type="email" class="form-control @error('email') is-invalid @enderror shadow-none"
                        id="email" placeholder="Adresse email*" wire:model="email" required>
                    @error('email')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

        </div>


        <div class="form-group" style="position: relative;">
            <span for="small">Mot de passe</span>
            <span class="text-danger">*</span>
            <input type="password" placeholder="Mot de passe" class="form-control  shadow-none" id="password"
                wire:model="password" required>
            <button class="password_show" type="button">
                <span class="input-group-text" id="showPassword">
                    <i class="bi bi-eye"></i>
                </span>
            </button>
        </div>
        @error('password')
            <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <div class="fil-import-registrer">
            <input type="file" class="d-none" id="photo" wire:model="photo">
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
        <input type="checkbox" id="shop">
        <b>
            <a href="#">je suis une boutique</a>
        </b>
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
            <input type="file" class="d-none" name="matricule" id="matricule" wire:model="matricule">
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
    <br>

    </div>


    <div class="p-1">
        <input type="checkbox" id="accept" wire:model="accept" required> j'ai lu
        <b>
            <i class="bi bi-link-45deg"></i>
            <a href="/conditions" target="__blank">Les Conditions générales</a>
        </b>
        et j'accepte !
        @error('accept')
            <br>
            <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>



    <div class="d-flex justify-content-end">


        <div>
            <button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium" id="submit">
                <span wire:loading>
                    <x-Loading></x-Loading>
                </span>
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
</script>
