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
            <input type="text" placeholder="Nom et prénom" class="form-control @error('nom') is-invalid @enderror shadow-none"
                 wire:model="nom" required>
            @error('nom')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <span for="small">Username</span>
                    <input type="tel" class="form-control @error('username') is-invalid @enderror shadow-none"id="username"
                        placeholder="username23"  
                        wire:model="username" required>
                    @error('username')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <span for="small">Numéro de téléphone</span>
                    <input type="tel" class="form-control @error('telephone') is-invalid @enderror shadow-none" id="telephone"
                        placeholder="Numéro de téléphone*" value="+212" 
                        wire:model="telephone" required>
                    @error('telephone')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <span for="small">Adresse email</span>
            <input type="email" class="form-control @error('email') is-invalid @enderror shadow-none" id="email" placeholder="Adresse email*"
                 wire:model="email" required>
            @error('email')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <span for="small">Mot de passe</span>
            <div class="input-group mb-3">
                <input type="password" placeholder="Mot de passe" class="form-control  shadow-none"
                    id="password" wire:model="password" required>
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
       <div class="div-2 d-none">
        <div class="form-group">
            <label for="">Matricule fiscal </label>
            <input type="file" class="form-control shadow-none" @error('matricule') is-invalid @enderror
                wire:model="matricule">
            @error('matricule')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
            <span class="text-danger small">
                *
                Veuillez télécharger votre matricule fiscale si et seulement si vous etes une boutique.
            </span>
        </div>
    </div>
       <br>

    </div>



    


    <div class="d-flex justify-content-end">


        <div>
            <button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium">
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

    //show romove d-none class on div-2 if shop is checked
    let checkboxShop = document.getElementById('shop');
    checkboxShop.addEventListener('change', ()=>{
       if(checkboxShop.checked){
           document.querySelector('.div-2').classList.remove('d-none')   ;
       }else{
        document.querySelector('.div-2').classList.add('d-none')   ;
       }
    });
</script>
