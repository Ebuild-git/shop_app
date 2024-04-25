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
                            Connectez-vous pour vendre et acheter sur SHOP<span class="color">IN</span>.
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
                                <input type="tel"class="form-control" value="{{ old('username') }}" id="username"
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
                                            <option value="{{ $year }}" @selected($i == old('annee'))>
                                                {{ $year }}
                                            </option>
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
                            <div class="form-group">
                                <span for="small">Adresse</span>
                                <input type="text" class="form-control" value="{{ old('adress') }}" name="adress">
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
                                <input type="password" placeholder="Mot de passe" class="form-control" id="password-1"
                                    name="password" value="{{ old('password') }}" required>
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
                                    value="{{ old('password_confirmation') }}" min="" id="password-2"
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
                        En cliquant sur S’inscrire, vous acceptez nos
                        <a href="/conditions" target="__blank"> <b>Conditions générales</b> </a>.
                        Vous recevrez peut-être des notifications
                        par texto de notre part et vous pouvez à tout moment vous désabonner.
                    </div>

                    <br>
                    <div>
                        <button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium"
                            id="submit" wire:loading.attr="disabled">
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
                var regex = /^[a-z0-9-]+$/;

                if (!regex.test(input)) {
                    errorMessage =
                        "Le nom d'utilisateur doit contenir uniquement des lettres minuscules, des chiffres et des tirets (-).";
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

@endsection
