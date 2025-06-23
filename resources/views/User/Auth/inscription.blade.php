@extends('User.fixe')
@section('titre', 'Inscription')
@section('content')
@section('body')

    <div class="container pt-5 pb-5" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
        <div class="row">
            <div class="col-sm-6 ">
                <div class="p-3 ">
                    <div class="p-3 ">
                        <h4>
                            {!! __('login_to_sell_and_buy') !!}
                        </h4>
                        <img style="width: 80%;" src="/icons/illus-register2.png" alt="" srcset="">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 ">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{!! $error !!}</li>
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
                                <span for="small text-muted">{{ __('nom') }}</span>
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
                                <span for="small text-muted">{{ __('prenom') }}</span>
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
                                <span for="small">{{ __('pseudonyme') }}</span>
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
                            <div class="form-group" style="position: relative; min-height: 80px;">
                                <img src="/icons/maroc.webp" height="30" alt="" class="position-absolute"
                                    style="bottom:18px; {{ app()->getLocale() == 'ar' ? 'right: 14px;' : 'left: 14px;' }}; border-radius: 100%;">
                                <span for="small">{{ __('telephone') }}</span>
                                <span class="text-danger">*</span>
                                <input type="tel" style="{{ app()->getLocale() == 'ar' ? 'padding-right: 50px;' : 'padding-left: 50px;' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="form-control" maxlength="14"
                                    value="{{ old('telephone') }}" oninput="formatTelephone(this)" id="telephone"
                                    placeholder="00 00 00 00 00" name="telephone" required>
                                @error('telephone')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <span for="small">{{ __('genre') }}</span>
                                <span class="text-danger">*</span>
                                <div class="input-group">
                                    <button type="button" class="form-control register-button"
                                        onclick="selectButton(this,'male')">
                                        <img width="20" height="20"
                                            src="https://img.icons8.com/sf-black/20/008080/male.png" alt="male" />
                                            {{ __('male') }}
                                    </button>
                                    <button type="button" class="form-control register-button"
                                        onclick="selectButton(this,'female')">
                                        <img width="20" height="20"
                                            src="https://img.icons8.com/ios-filled/20/008080/female.png" alt="female" />
                                            {{ __('female') }}
                                    </button>
                                </div>
                                <input type="hidden" id="sexe-input" name="genre" value="{{ old('genre') }}">
                                @error('genre')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <span for="small">{{ __('date_naissance') }}</span>
                                <span class="text-danger">*</span>
                                <div class="input-group">
                                    <select name="jour" class="form-control">
                                        <option value="">{{ __('day') }}</option>
                                        @for ($i = 1; $i <= 31; $i++)
                                            <option value="{{ $i }}" @selected($i == old('jour'))>
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                    @php
                                        setlocale(LC_TIME, 'fr_FR');
                                    @endphp
                                    <select name="mois" class="form-control" required>
                                        <option value="">{{ __('month')}}</option>
                                        <option @selected(1 == old('mois')) value="1">{{ __('january')}}</option>
                                        <option @selected(2 == old('mois')) value="2">{{ __('february') }}</option>
                                        <option @selected(3 == old('mois')) value="3">{{ __('march') }}</option>
                                        <option @selected(4 == old('mois')) value="4">{{ __('april') }}</option>
                                        <option @selected(5 == old('mois')) value="5">{{ __('may') }}</option>
                                        <option @selected(6 == old('mois')) value="6">{{ __('june') }}</option>
                                        <option @selected(7 == old('mois')) value="7">{{ __('july') }}</option>
                                        <option @selected(8 == old('mois')) value="8">{{ __('august') }}</option>
                                        <option @selected(9 == old('mois')) value="9">{{ __('september') }}</option>
                                        <option @selected(10 == old('mois')) value="10">{{ __('october') }}</option>
                                        <option @selected(11 == old('mois')) value="11">{{ __('november') }}</option>
                                        <option @selected(12 == old('mois')) value="12">{{ __('december') }}</option>
                                    </select>
                                    <select name="annee" class="form-control" required>
                                        <option value="">{{ __('year')}}</option>
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
                                <span for="small">{{ __('email') }}</span>
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
                                    <label for="adress">{{ __('ville') }}</label>
                                    <span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="adresse" value="{{ old('adresse') }}">
                                    @error('adress')
                                        <small class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-sm-6">
                                    <label for="rue">{{ __('rue') }}</label>
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
                                    <label for="nom_batiment">{{ __('nom_batiment') }}</label>
                                    <span class="text-danger">*</span>
                                    <input type="text" class="form-control" value="{{ old('nom_batiment') }}" name="nom_batiment">
                                    @error('nom_batiment')
                                        <small class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label for="etage">{{ __('etage') }}</label>
                                    <input type="text" class="form-control" value="{{ old('etage') }}" name="etage">
                                    @error('etage')
                                        <small class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-sm-4">
                                    <label for="num_appartement">{{ __('num_appartement') }}</label>
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
                                <span for="small">{{ __('mot_de_passe') }}</span>
                                <span class="text-danger">*</span>
                                <input type="password" placeholder="{{ __('password_placeholder') }}" class="form-control" id="password-1"
                                    name="password" value="{{ old('password') }}" minlength="8" required>
                                <button class="password_show" type="button" onclick="showPassword(1)" style="{{ App::isLocale('ar') ? 'left: 0; right: auto;' : 'right: 0; left: auto;' }} position: absolute; top: 45%; transform: translateY(-50%);">
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
                                <span for="small">{{ __('confirmation_mot_de_passe') }}</span>
                                <span class="text-danger">*</span>
                                <input type="password" placeholder="{{ __('password_placeholder') }}" class="form-control"
                                    value="{{ old('password_confirmation') }}" minlength="8" id="password-2"
                                    name="password_confirmation" required>
                                <button class="password_show" type="button" onclick="showPassword(2)" style="{{ App::isLocale('ar') ? 'left: 0; right: auto;' : 'right: 0; left: auto;' }} position: absolute; top: 45%; transform: translateY(-50%);">
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
                        <p>{{ __('terms_condition') }}</p>
                        <input type="checkbox" id="acceptConditions" onclick="toggleSubmitButton()">
                        <label for="acceptConditions">
                            {!! __('accept_conditions') !!}
                        </label>
                        <img src="check.svg" alt="Coche" class="checkmark" style="display: none;">
                        <p>{{ __('receive_notifications') }}</p>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium"
                            id="submit" wire:loading.attr="disabled" disabled>
                            <span wire:loading>
                                <x-Loading></x-Loading>
                            </span>
                            {{ __('sign_up') }}
                            <i class="bi bi-arrow-right-circle-fill"></i>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
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
    </script>
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


        const translations = @json([
            'username_invalid' => __('username_invalid'),
        ]);
        //gestion pseudonyme
        $(document).ready(function() {
            $('#username').on('input', function(event) {
                var input = $(this).val();
                var errorMessage = '';

                // Expression régulière pour valider le format du nom d'utilisateur
                var regex = /^[a-zA-Z0-9-!@#\$%\^&\*\(\)_\+]+$/;
                if (!regex.test(input)) {
                    errorMessage = translations.username_invalid;
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
