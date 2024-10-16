@extends('User.fixe')
@section('titre', 'Inscription')
@section('content')
@section('body')

    <div class="container pt-5 pb-5">
        <div class="row">
            <div class="col-sm-6 ">
                <div class="p-3 ">
                    <div class="p-3 ">
                        <h4>
                            Connectez-vous pour vendre et acheter sur SHOP<span class="color">IN</span>
                        </h4>
                        <img style="width: 80%;" src="/icons/illus-register.png" alt="" srcset="">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 ">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="post" action="{{ route('inscription') }}">
                    @csrf

                    @include('components.alert-livewire')

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <span for="small text-muted">Nom</span>
                                <span class="text-danger">*</span>
                                <input type="text" class="form-control" name="nom" value="{{ old('nom') }}"
                                    required>
                                @error('nom')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <span for="small text-muted">Prénom</span>
                                <span class="text-danger">*</span>
                                <input type="text"class="form-control" name="prenom" value="{{ old('prenom') }}"
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
                                <input type="text"class="form-control" value="{{ old('username') }}" id="username"
                                    name="username" required>
                                <div id="error-message" style="color: red;"></div>
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
                                <input type="tel" style="padding-left: 50px;" class="form-control" maxlength="14"
                                    value="{{ old('telephone') }}" oninput="formatTelephone(this)" id="telephone"
                                    placeholder="00 00 00 00 00" name="telephone" required>
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
                                    <buttonn type="button" class="form-control register-button"
                                        onclick="selectButton(this,'male')">
                                        <img width="20" height="20"
                                            src="https://img.icons8.com/sf-black/20/008080/male.png" alt="male" />
                                        Homme
                                    </buttonn>
                                    <buttonn type="button" class="form-control register-button"
                                        onclick="selectButton(this,'female')">
                                        <img width="20" height="20"
                                            src="https://img.icons8.com/ios-filled/20/008080/female.png" alt="female" />
                                        Femme
                                    </buttonn>
                                </div>
                                <input type="hidden" id="sexe-input" name="genre" value="{{ old('genre') }}">
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
                                    <select name="jour" class="form-control">
                                        <option value="">Jour</option>
                                        @for ($i = 1; $i <= 31; $i++)
                                            <option value="{{ $i }}" @selected($i == old('jour'))>
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                    @php
                                        setlocale(LC_TIME, 'fr_FR');
                                    @endphp
                                    <select name="mois" class="form-control" required>
                                        <option value="">Mois</option>
                                        <option @selected(1 == old('mois')) value="1">Janvier</option>
                                        <option @selected(2 == old('mois')) value="2">Février</option>
                                        <option @selected(3 == old('mois')) value="3">Mars</option>
                                        <option @selected(4 == old('mois')) value="4">Avril</option>
                                        <option @selected(5 == old('mois')) value="5">Mai</option>
                                        <option @selected(6 == old('mois')) value="6">Juin</option>
                                        <option @selected(7 == old('mois')) value="7">Juillet</option>
                                        <option @selected(8 == old('mois')) value="8">Août</option>
                                        <option @selected(9 == old('mois')) value="9">Septembre</option>
                                        <option @selected(10 == old('mois')) value="10">Octobre</option>
                                        <option @selected(11 == old('mois')) value="11">Novembre</option>
                                        <option @selected(12 == old('mois')) value="12">Décembre</option>
                                    </select>
                                    <select name="annee" class="form-control" required>
                                        <option value="">Année</option>
                                        @for ($year = date('Y'); $year >= date('Y') - 100; $year--)
                                        <option value="{{ $year }}" @selected($year == old('annee'))>{{ $year }}</option>
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
                                <input type="email" class="form-control" value="{{ old('email') }}" name="email"
                                    required>
                                @error('email')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="adress">Ville</label>
                                    <span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="adresse" value="{{ old('adresse') }}">
                                    @error('adress')
                                        <small class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-sm-6">
                                    <label for="rue">Rue</label>
                                    <span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="ruee" value="{{ old('ruee') }}">
                                    @error('rue')
                                        <small class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="nom_batiment">Nom Bâtiment</label>
                                    <span class="text-danger">*</span>
                                    <input type="text" class="form-control" value="{{ old('nom_batiment') }}" name="nom_batiment">
                                    @error('nom_batiment')
                                        <small class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label for="etage">Étage</label>
                                    <input type="text" class="form-control" value="{{ old('etage') }}" name="etage">
                                    @error('etage')
                                        <small class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-sm-4">
                                    <label for="num_appartement">Numéro appartement</label>
                                    <input type="text" class="form-control" value="{{ old('num_appartement') }}" name="num_appartement">
                                    @error('num_appartement')
                                        <small class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group" style="position: relative;">
                                <span for="small">Mot de passe</span>
                                <span class="text-danger">*</span>
                                <input type="password" placeholder="Mot de passe" class="form-control" id="password-1"
                                    name="password" value="{{ old('password') }}" minlength="8" required>
                                <button class="password_show" type="button" onclick="showPassword(1)">
                                    <span class="input-group-text">
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
                                <input type="password" placeholder="Mot de passe" class="form-control"
                                    value="{{ old('password_confirmation') }}" minlength="8" id="password-2"
                                    name="password_confirmation" required>
                                <button class="password_show" type="button" onclick="showPassword(2)">
                                    <span class="input-group-text">
                                        <i class="bi bi-eye"></i>
                                    </span>
                                </button>

                            </div>
                            @error('password')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="p-1">
                        <p>Veuillez lire attentivement et accepter nos conditions générales avant de continuer.</p>
                        <input type="checkbox" id="acceptConditions" onclick="toggleSubmitButton()">
                        <label for="acceptConditions">
                            J'accepte les
                            <a href="/conditions" target="__blank" style="color: black;"><b>Conditions générales</b></a>.
                        </label>
                        <img src="check.svg" alt="Coche" class="checkmark" style="display: none;">
                        <p>Vous pourrez recevoir des SMS ou des e-mails pour des mises à jour. Vous pouvez vous désabonner à tout moment.</p>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium"
                            id="submit" wire:loading.attr="disabled" disabled>
                            <span wire:loading>
                                <x-Loading></x-Loading>
                            </span>
                            S'inscrire
                            <i class="bi bi-arrow-right-circle-fill"></i>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>



    <script>
        //formatage du numero de telephone
        function formatTelephone(input) {
            var phoneNumber = input.value.replace(/\D/g, ''); // Supprime tous les caractères non numériques
            phoneNumber = phoneNumber.substring(0, 10); // S'assure que le numéro a au plus 10 chiffres
            var formattedPhoneNumber = '';
            for (var i = 0; i < phoneNumber.length; i++) {
                formattedPhoneNumber += phoneNumber[i];
                if ((i + 1) % 2 === 0 && i < phoneNumber.length - 1) {
                    formattedPhoneNumber += ' ';
                }
            }
            input.value = formattedPhoneNumber;
        }




        //gestion de a selection des sexes
        function selectButton(button, sexe) {
            var buttons = document.querySelectorAll('.register-button');
            buttons.forEach(function(btn) {
                if (btn !== button) {
                    btn.classList.remove('selected-register');
                }
            });
            button.classList.toggle('selected-register');
            document.getElementById('sexe-input').value = sexe;
        }


        //gestion pseudonyme
        $(document).ready(function() {
            $('#username').on('input', function(event) {
                var input = $(this).val();
                var errorMessage = '';

                // Expression régulière pour valider le format du nom d'utilisateur
                var regex = /^[a-zA-Z0-9-!@#\$%\^&\*\(\)_\+]+$/;
                if (!regex.test(input)) {
                    errorMessage =
                        "Le pseudonyme doit contenir uniquement des lettres , des chiffres et des caractères spéciaux (-!@# etc.).";
                }

                // Afficher le message d'erreur
                $('#error-message').text(errorMessage);

                // Si un caractère invalide est saisi, supprimer le dernier caractère
                if (errorMessage !== '') {
                    $(this).val(input.slice(0, -1));
                }
            });
        });
    </script>
    <script>
        function toggleSubmitButton() {
            var submitButton = document.getElementById('submit');
            var checkbox = document.getElementById('acceptConditions');
            submitButton.disabled = !checkbox.checked;
        }
    </script>
@endsection
