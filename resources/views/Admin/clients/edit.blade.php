@extends('Admin.fixe')
@section('titre', 'Modifier le Profil')
@section('body')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Paramètres /</span> Mon Profil</h4>

        <div class="row">
            <div class="col-md-12">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- ===================== UpdateInformations fields ===================== --}}
                <div class="card mb-4">
                    <h5 class="card-header">Informations Personnelles</h5>
                    <form action="{{ route('admin.profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Avatar --}}
                        <div class="card-body">
                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                <img src="{{ $user->getAvatar() }}" alt="user-avatar"
                                    class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" />
                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-primary me-2 mb-3" tabindex="0">
                                        <span class="d-none d-sm-block">Changer la photo</span>
                                        <i class="ti ti-upload d-block d-sm-none"></i>
                                        <input type="file" id="upload" name="avatar" class="account-file-input" hidden
                                            accept="image/png, image/jpeg, image/webp" />
                                    </label>
                                    <p class="text-muted mb-0">Autorisé : JPG, PNG, WEBP. Taille max : 2Mo</p>
                                </div>
                            </div>
                        </div>

                        <hr class="my-0" />

                        <div class="card-body">
                            <div class="row">

                                {{-- Email --}}
                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
                                    <input class="form-control @error('email') is-invalid @enderror"
                                        type="email" id="email" name="email"
                                        value="{{ old('email', $user->email) }}" required />
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Phone --}}
                                <div class="mb-3 col-md-6">
                                    <label for="phone_number" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                    <input class="form-control @error('phone_number') is-invalid @enderror"
                                        type="text" id="phone_number" name="phone_number"
                                        value="{{ old('phone_number', $user->phone_number) }}" required />
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Region --}}
                                <div class="mb-3 col-md-6">
                                    <label for="region" class="form-label">Région <span class="text-danger">*</span></label>
                                    <select id="region" name="region"
                                        class="form-select @error('region') is-invalid @enderror" required>
                                        <option value="">Sélectionner</option>
                                        @foreach ($regions as $r)
                                            <option value="{{ $r->id }}"
                                                {{ old('region', $user->region) == $r->id ? 'selected' : '' }}>
                                                {{ $r->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('region')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Address --}}
                                <div class="mb-3 col-md-6">
                                    <label for="address" class="form-label">Adresse</label>
                                    <input class="form-control @error('address') is-invalid @enderror"
                                        type="text" id="address" name="address"
                                        value="{{ old('address', $user->address) }}" />
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Rue --}}
                                <div class="mb-3 col-md-6">
                                    <label for="rue" class="form-label">Rue <span class="text-danger">*</span></label>
                                    <input class="form-control @error('rue') is-invalid @enderror"
                                        type="text" id="rue" name="rue"
                                        value="{{ old('rue', $user->rue) }}" required />
                                    @error('rue')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Nom bâtiment --}}
                                <div class="mb-3 col-md-4">
                                    <label for="nom_batiment" class="form-label">Nom du bâtiment <span class="text-danger">*</span></label>
                                    <input class="form-control @error('nom_batiment') is-invalid @enderror"
                                        type="text" id="nom_batiment" name="nom_batiment"
                                        value="{{ old('nom_batiment', $user->nom_batiment) }}" required />
                                    @error('nom_batiment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Étage --}}
                                <div class="mb-3 col-md-4">
                                    <label for="etage" class="form-label">Étage <span class="text-danger">*</span></label>
                                    <input class="form-control @error('etage') is-invalid @enderror"
                                        type="text" id="etage" name="etage"
                                        value="{{ old('etage', $user->etage) }}" required />
                                    @error('etage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- N° Appartement --}}
                                <div class="mb-3 col-md-4">
                                    <label for="num_appartement" class="form-label">N° Appartement <span class="text-danger">*</span></label>
                                    <input class="form-control @error('num_appartement') is-invalid @enderror"
                                        type="text" id="num_appartement" name="num_appartement"
                                        value="{{ old('num_appartement', $user->num_appartement) }}" required />
                                    @error('num_appartement')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Birthdate --}}
                                <div class="mb-3 col-md-6">
                                    <label for="birthdate" class="form-label">Date de naissance <span class="text-danger">*</span></label>
                                    <input class="form-control @error('birthdate') is-invalid @enderror"
                                        type="date" id="birthdate" name="birthdate"
                                        value="{{ old('birthdate', \Carbon\Carbon::parse($user->birthdate)->format('Y-m-d')) }}"
                                        required />
                                    @error('birthdate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <hr class="my-0" />

                        {{-- ===================== UpdateCordonnées fields ===================== --}}
                        <div class="card-body">
                            <h5 class="mb-4">Informations Bancaires</h5>
                            <div class="row">

                                {{-- Bank name --}}
                                <div class="mb-3 col-md-6">
                                    <label for="bank_name" class="form-label">Nom de la banque <span class="text-danger">*</span></label>
                                    <input class="form-control @error('bank_name') is-invalid @enderror"
                                        type="text" id="bank_name" name="bank_name"
                                        value="{{ old('bank_name', $user->bank_name) }}" required />
                                    @error('bank_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Titulaire --}}
                                <div class="mb-3 col-md-6">
                                    <label for="titulaire_name" class="form-label">Nom du titulaire <span class="text-danger">*</span></label>
                                    <input class="form-control @error('titulaire_name') is-invalid @enderror"
                                        type="text" id="titulaire_name" name="titulaire_name"
                                        value="{{ old('titulaire_name', $user->titulaire_name) }}" required />
                                    @error('titulaire_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- RIB — left blank intentionally (encrypted in DB) --}}
                                <div class="mb-3 col-md-12">
                                    <label for="rib_number" class="form-label">
                                        Numéro RIB <span class="text-danger">*</span>
                                        <small class="text-muted">(24 chiffres)</small>
                                    </label>
                                    <input class="form-control @error('rib_number') is-invalid @enderror"
    type="text" id="rib_number" name="rib_number"
    maxlength="24" placeholder="Saisir les 24 chiffres du RIB"
    value="{{ old('rib_number', $decryptedRib) }}" required />
                                    @error('rib_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- CIN image --}}
                                <div class="mb-3 col-md-12">
                                    <label for="cin_img" class="form-label">Carte d'identité (CIN)</label>
                                    @if ($user->cin_img)
                                        <div class="mb-2 d-flex align-items-center gap-2">
                                            <img src="{{ Storage::url($user->cin_img) }}" alt="CIN actuel"
                                                style="max-height: 80px;" class="rounded border" />
                                            <span class="text-muted small">Image actuelle
                                                @if ($user->cin_approved)
                                                    <span class="badge bg-success ms-1">Approuvée</span>
                                                @else
                                                    <span class="badge bg-warning ms-1">En attente</span>
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                    <input type="file" id="cin_img" name="cin_img"
                                        class="form-control @error('cin_img') is-invalid @enderror"
                                        accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" />
                                    @error('cin_img')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">Enregistrer les changements</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.getElementById('upload').addEventListener('change', function(evt) {
            const [file] = evt.target.files;
            if (file) {
                document.getElementById('uploadedAvatar').src = URL.createObjectURL(file);
            }
        });
    </script>
@endsection
