<form wire:submit="inscription">

    @include('components.alert-livewire')


    <div class="div-1">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <span for="small text-muted">Nom</span>
                    <span class="text-danger">*</span>
                    <input type="text" placeholder="Nom "
                        class="form-control @error('nom') is-invalid @enderror shadow-none" wire:model="nom" required>
                    @error('nom')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <span for="small text-muted">Prénom</span>
                    <span class="text-danger">*</span>
                    <input type="text" placeholder="Prénom"
                        class="form-control @error('prenom') is-invalid @enderror shadow-none" wire:model="prenom"
                        required>
                    @error('prenom')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <span for="small">Pseudonyme</span>
                    <span class="text-danger">*</span>
                    <input type="tel" class="form-control @error('username') is-invalid @enderror shadow-none"
                        id="pseudonyme" placeholder="Pseudonyme" wire:model.live="username" required>
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
            <div class="col-sm-12">
                <div class="form-group">
                    <span for="small">Genre</span>
                    <span class="text-danger">*</span>
                    <div class="input-group">
                        <buttonn type="button"
                            class="form-control {{ $genre == 'Masculin' ? 'selected-register' : '' }}"
                            wire:click="set_genre('Masculin')">
                            <img width="20" height="20" src="https://img.icons8.com/sf-black/20/008080/male.png"
                                alt="male" />
                            Homme
                        </buttonn>
                        <buttonn type="button"
                            class="form-control {{ $genre == 'Féminin' ? 'selected-register' : '' }}"
                            wire:click="set_genre('Féminin')">
                            <img width="20" height="20"
                                src="https://img.icons8.com/ios-filled/20/008080/female.png" alt="female" />
                            Femme
                        </buttonn>
                    </div>
                    @error('genre')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="col-sm-12">
                <div class="form-group">
                    <span for="small">Date de naissance</span>
                    <span class="text-danger">*</span>
                    <div class="input-group">
                        <select wire:model="jour" class="form-control">
                            <option selected disabled>Jour</option>
                            @for ($i = 1; $i <= 31; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        <select wire:model="mois" class="form-control">
                            <option selected disabled>Mois</option>
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}">{{ strftime('%B', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endforeach
                        </select>
                        <select wire:model="annee" class="form-control">
                            <option selected disabled>Année</option>
                            @for ($year = date('Y'); $year >= date('Y') - 100; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>

                    @error('jour')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                    @error('mois')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                    @error('annee')
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
            <div class="col-sm-12">
                <div class="form-group">
                    <span for="small">Adresse</span>
                    <span class="text-danger">*</span>
                    <input type="text" class="form-control @error('adress') is-invalid @enderror shadow-none"
                        id="adress" placeholder="Adresse *" wire:model="adress" required>
                    @error('adress')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

        </div>


        <div class="row">
            <div class="col-sm-6">
                <div class="form-group" style="position: relative;">
                    <span for="small">Mot de passe</span>
                    <span class="text-danger">*</span>
                    <input type="password" placeholder="Mot de passe" class="form-control  shadow-none"
                        id="password" wire:model="password" required>
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
            <div class="col-sm-6">
                <div class="form-group" style="position: relative;">
                    <span for="small">Confirmation du mot de passe</span>
                    <span class="text-danger">*</span>
                    <input type="password" placeholder="Mot de passe" class="form-control  shadow-none"
                        id="password_confirmation" wire:model="password_confirmation" required>
                    <button class="password_show" type="button">
                        <span class="input-group-text" id="showPassword2">
                            <i class="bi bi-eye"></i>
                        </span>
                    </button>
                </div>
                @error('password')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>
    {{--  <div class="form-group">
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

    </div> --}}
    {{--  <div class="p-1">
        <input type="checkbox" id="shop">
        <b>
            <a href="#">je suis une boutique</a>
        </b>
    </div>

    @error('matricule')
        <small class="form-text text-danger">{{ $message }}</small>
    @enderror --}}
    @if ($matricule)
        <div class="div-2">
        @else
            <div class="div-2 d-none">
    @endif

    {{-- <div class="form-group">
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

    </div> --}}
    <br>

    </div>


    <div class="p-1">
        En cliquant sur S’inscrire, vous acceptez nos
        <a href="/conditions" target="__blank"> <b>Conditions générales</b> </a>.
        Vous recevrez peut-être des notifications
        par texto de notre part et vous pouvez à tout moment vous désabonner.
    </div>

    <br>
    <div>
        <button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium" id="submit" wire:loading.attr="disabled">
            <span wire:loading>
                <x-Loading></x-Loading>
            </span>
            S'inscrire
            <i class="bi bi-arrow-right-circle-fill"></i>
        </button>
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

    document.getElementById("showPassword2").addEventListener("click", function() {
        var y = document.getElementById("password_confirmation");
        if (y.type === "password") {
            y.type = "text";
        } else {
            y.type = "password";
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
