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



    <div class="div-1">
        <span class="h5">
            Etape : <span class="link">1</span> /2
        </span>
        <hr>


        <div class="form-group">
            <input type="text" placeholder="Nom et prénom" class="form-control form-control-ps shadow-none"
                @error('nom') is-invalid @enderror wire:model="nom" id="nom" required>
            @error('nom')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <div class="input-group ">
                <span class="input-group-text" style="padding: 1px !important;" id="basic-addon1">
                    <img width="28" height="28"
                        src="https://img.icons8.com/color/48/morocco-circular.png" alt="morocco-circular" />
                </span>
                <input type="tel" class="form-control form-control-ps shadow-none" id="telephone"
                    placeholder="Numéro de téléphone*" value="+212"
                    @error('telephone') is-invalid @enderror wire:model="telephone" required>
            </div>
            @error('telephone')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <input type="email" class="form-control shadow-none" id="email"
                placeholder="Adresse email*" @error('email') is-invalid @enderror wire:model="email"
                required>
            @error('email')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <div class="input-group mb-3">
                <input type="password" placeholder="Mot de passe" class="form-control form-control-ps shadow-none"
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
            <label>Photo de profil</label>
            <div class="fil-import-registrer">
                <input type="file" class="d-none" id="photo" wire:model="photo" required>
                <span id="select-image">
                    @if ($photo)
                        <img src="{{ $photo->temporaryUrl() }}" alt="" class="avatar-inscription">
                    @else
                        <img src="https://img.icons8.com/external-smashingstocks-detailed-outline-smashing-stocks/66/FFFFFF/external-file-upload-network-and-communication-smashingstocks-detailed-outline-smashing-stocks.png"
                            alt="" class="avatar-inscription">
                    @endif
                    <br>
                    <i>Veuillez selectionner une image </i>
                </span>
            </div>
            @error('photo')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror

        </div>
        <div class="modal-footer">
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-dark" id="suivant">
                    Suivant
                    <i class="bi bi-arrow-right-circle-fill"></i>
                </button>
            </div>
        </div>

    </div>



    <div class="div-2 ">
        <span class="h5">
            Etape : <span class="link">2</span> /2
        </span>
        <hr>
        <p class="text-muted">
            Veuillez mentionner le type de compte que vous souhaitez enregistrer. Veuillez noter que selon
            le type de
            compte choisi (personnel ou boutique), vous aurez accès à des fonctionnalités différentes.
        </p>
        <p>

        </p>
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
        <br><br>
        <div>
            <div class="d-flex justify-content-between">

                <div>
                    <button type="button" class="btn btn-sm btn-dark" id="retour">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        Retour
                    </button>
                </div>
                <div>
                    <button type="submit" class="btn btn-sm bg-red">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                            wire:loading></span>
                        Terminer l'inscription
                        <i class="bi bi-arrow-right-circle-fill"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>


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


        //when the file input is changed, run this action
        $("#suivant").on('click', function() {
            var next = true;
            //verifier que les champs : nom,email,password,telephone,photo sont remplis avant d'aller a l'etape 2

            if ($("#nom").val().length == 0) {
                next = false;
                return false;
            }
            if ($("#email").val().length == 0) {
                next = false;
                return false;
            }
            if ($("#telephone").val().length == 0) {
                next = false;
                return false;
            }
            if ($("#password").val().length == 0) {
                next = false;
                return false;
            }

            //verifier que une photo a ete selectionner
            if (!$("#photo")[0].files.length) {
                next = false;
                return false;
            }

            if (next) {
                $(".div-1").hide("slow");
                $(".div-2").show("slow");
            }

        });

        function check_input() {

        }


        $("#retour").on('click', function() {
            $(".div-1").show("slow");
            $(".div-2").hide("slow");
        });
    </script>
    @if ($nom && $email && $password && $telephone && $photo)
        <style>
            .div-1 {
                display: none;
            }
        </style>
    @else
        <style>
            .div-2 {
                display: none;
            }
        </style>
    @endif
</form>
