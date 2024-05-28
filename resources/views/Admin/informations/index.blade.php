@extends('Admin.fixe')
@section('titre', 'Configuration des Informations')
@section('content')



@section('body')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="py-3 mb-4">
            <span class="text-muted fw-light">Configuration /</span> Informations du site
        </h5>

        <div class="card">
            <form method="POST" action="{{ route('update_information_website') }}">
                @csrf
                <div class="row p-2">
                    <div class="col-sm-4 my-auto">
                        <h5 class="card-header">
                            Configuration des informations du site.
                        </h5>
                    </div>
                </div>
                @include('components.alert-livewire')
                <div class="row p-3">
                    <div class="col-sm-8">
                        <h5>Contacts direct</h5>
                        <hr>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="">Numéro de téléphone</label>
                                    <input type="tel" name="telephone"
                                        value="{{ old('phone_number', $configuration->phone_number) }}" class="form-control">
                                    @error('phone_number')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="">Adresse E-mail</label>
                                    <input type="email" name="email" value="{{ old('email', $configuration->email) }}"
                                        class="form-control">
                                    @error('email')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label for="">Adresse de localisation</label>
                                <input type="text" name="adresse" value="{{ old('adresse', $configuration->adresse) }}"
                                    class="form-control">
                                @error('adresse')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="">Adresse email d'envoie des messages</label>
                                <input type="email" name="email_send_message"
                                    value="{{ old('email_send_message', $configuration->email_send_message) }}"
                                    class="form-control">
                                @error('email_send_message')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <br><br>
                        <h5>Réseau sociaux</h5>
                        <hr>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="">Url facebook</label>
                                    <input type="url" name="facebook" value=" {{ old('facebook', $configuration->facebook) }}"
                                        class="form-control">
                                    @error('facebook')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="">Url tiktok</label>
                                    <input type="url" name="tiktok" value="{{ old('tiktok', $configuration->tiktok) }}"
                                        class="form-control">
                                    @error('tiktok')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="">Url instagram</label>
                                    <input type="url" name="instagram"
                                        value="{{ old('instagram', $configuration->instagram) }}" class="form-control">
                                    @error('instagram')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="">Url linkedin</label>
                                    <input type="url" name="linkedin" value="{{ old('linkedin', $configuration->linkedin) }}"
                                        class="form-control">
                                    @error('linkedin')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <h5>Configuration</h5>
                        <hr>
                        <div class="mb-3 mb-3">
                            <input type="checkbox" class="form-check-input" name='valider_photo'
                                @checked($configuration->valider_photo)>
                            Valider les photos de profils des utilisateurs a chaque changement.
                            @error('valider_photo')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 mb-3">
                            <input type="checkbox" class="form-check-input" name='valider_publication'
                                @checked($configuration->valider_publication)>
                            Valider toutes les nouvelles publications des utilisateurs.
                            @error('valider_publication')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            Enregistrer les modifications
                        </button>
                    </div>
            </form>
        </div>


    </div>
    <!--/ Content -->
@endsection
@section('script')
    <script src="/assets-admin/vendor/libs/jquery/jquery.js"></script>
    <script src="/assets-admin/vendor/libs/popper/popper.js"></script>
    <script src="/assets-admin/vendor/js/bootstrap.js"></script>
    <script src="/assets-admin/vendor/libs/node-waves/node-waves.js"></script>
    <script src="/assets-admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="/assets-admin/vendor/libs/hammer/hammer.js"></script>
    <script src="/assets-admin/vendor/libs/i18n/i18n.js"></script>
    <script src="/assets-admin/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="/assets-admin/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="/assets-admin/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="/assets-admin/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>

    <!-- Main JS -->
    <script src="/assets-admin/js/main.js"></script>

    <!-- Page JS -->
    <script src="/assets-admin/js/app-logistics-dashboard.js"></script>
@endsection
