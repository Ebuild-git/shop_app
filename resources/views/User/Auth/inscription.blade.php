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
                <form method="post" action="{{ route('inscription') }}" enctype="multipart/form-data">
                    @csrf

                    @include('components.alert-livewire')

                    <div class="row">
                        <div class="col-sm-12 mb-4">
                            <div class="text-center">
                                <div class="avatar-upload-wrapper">
                                    <div class="avatar-upload-container" onclick="document.getElementById('avatar-input').click()">
                                        <img id="avatar-preview" src="https://t3.ftcdn.net/jpg/05/00/54/28/360_F_500542898_LpYSy4RGAi95aDim3TLtSgCNUxNlOlcM.jpg" alt="Avatar" class="avatar-preview-img">
                                        <div class="avatar-overlay">
                                            <i class="bi bi-camera-fill"></i>
                                        </div>
                                    </div>
                                    <input type="file" id="avatar-input" name="photo" accept="image/*" class="d-none" onchange="previewAvatar(event)">
                                </div>
                                <p class="avatar-hint text-muted mt-2">
                                    <small>{{ __('add_avatar') }} ({{ __('optional') }})</small>
                                </p>
                                @error('photo')
                                    <small class="form-text text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

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
                                        data-gender="male"
                                        onclick="selectButton(this,'male')">
                                        <img width="20" height="20"
                                            src="https://img.icons8.com/sf-black/20/008080/male.png" alt="male" />
                                            {{ __('male') }}
                                    </button>
                                    <button type="button" class="form-control register-button"
                                        data-gender="female"
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

        document.addEventListener('DOMContentLoaded', function () {
            var oldGenre = "{{ old('genre') }}";
            if (oldGenre) {
                var buttons = document.querySelectorAll('.register-button');
                buttons.forEach(function (btn) {
                    if (btn.dataset.gender === oldGenre) {
                        btn.classList.add('selected-register');
                        document.getElementById('sexe-input').value = oldGenre;
                    }
                });
            }
        });

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
            var phoneNumber = input.value.replace(/\D/g, '');
            phoneNumber = phoneNumber.substring(0, 10);
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
        $(document).ready(function() {
            $('#username').on('input', function(event) {
                var input = $(this).val();
                var errorMessage = '';

                var regex = /^[a-zA-Z0-9-!@#\$%\^&\*\(\)_\+]+$/;
                if (!regex.test(input)) {
                    errorMessage = translations.username_invalid;
                }

                $('#error-message').text(errorMessage);

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

    <style>
        .avatar-upload-wrapper {
            position: relative;
            display: inline-block;
        }
        .avatar-upload-container {
            position: relative;
            width: 100px;
            height: 100px;
            margin: 0 auto;
            border-radius: 50%;
            cursor: pointer;
            overflow: hidden;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .avatar-upload-container:hover {
            border-color: #008080;
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0,128,128,0.2);
        }
        .avatar-preview-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.3s ease;
        }
        .avatar-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0,128,128,0.8), rgba(0,100,100,0.8));
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .avatar-upload-container:hover .avatar-overlay {
            opacity: 1;
        }
        .avatar-overlay i {
            font-size: 28px;
        }
        .avatar-hint {
            font-size: 12px;
            color: #6c757d;
        }
        .avatar-hint i {
            color: #008080;
            margin-right: 4px;
        }
    </style>

    <script>
        function previewAvatar(event) {
            const input = event.target;
            const preview = document.getElementById('avatar-preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
