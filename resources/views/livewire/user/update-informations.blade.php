<form wire:submit="update">

    @include('components.alert-livewire')

    <div class="row">
        <div class="col-sm-4">*
            <label>Nom </label>
            <span class="text-danger">*</span>
            <div class="form-group">
                <input type="text" class="form-control border-r shadow-none" value="{{ Auth::user()->lastname }}" readonly>
            </div>
        </div>
        <div class="col-sm-4">
            <label>Prénom </label>
            <span class="text-danger">*</span>
            <div class="form-group">
                <input type="text" class="form-control border-r shadow-none" value="{{ Auth::user()->firstname }}" readonly>
            </div>
        </div>
        <div class="col-sm-4">
            <label>Pseudonyme </label>
                <span class="text-danger">*</span>
            <div class="form-group">
                <input type="text" class="form-control border-r shadow-none" value="{{ Auth::user()->username }}" readonly>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Adresse email</label>
                <span class="text-danger">*</span>
                <input type="email" class="form-control border-r shadow-none" @error('email') is-invalid @enderror
                    wire:model="email" required>
                @error('email')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Numéro de téléphone</label>
                <span class="text-danger">*</span>
                <input type="tel" class="form-control border-r shadow-none" oninput="formatTelephone(this)" maxlength="14"
                    @error('phone_number') is-invalid @enderror wire:model="phone_number" required>
                @error('phone_number')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4 mt-1">
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
                            <option value="{{ $m }}">
                                {{ strftime('%B', mktime(0, 0, 0, $m, 1)) }}
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

        <div class="col-sm-4">
            <div class="form-group">
                <label>Région</label>
                <span class="text-danger">*</span>
                <select class="form-control border-r shadow-none" wire:model="region" >
                    <option value=""></option>
                    @foreach ($regions as $item)
                        <option value="{{ $item->id }}">{{ $item->nom }}</option>
                    @endforeach
                </select>
                @error('region')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Ville</label>
                <span class="text-danger">*</span>
                <input type="text" class="form-control border-r shadow-none"
                    wire:model="address">
                @error('address')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Rue</label>
                <span class="text-danger">*</span>
                <input type="text" class="form-control border-r shadow-none"
                    wire:model="rue">
                @error('rue')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Nom Bâtiment</label>
                <span class="text-danger">*</span>
                <input type="text" class="form-control border-r shadow-none"
                    wire:model="nom_batiment">
                @error('nom_batiment')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label>Étage</label>
                <input type="text" class="form-control border-r shadow-none"
                    wire:model="etage">
                @error('etage')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label>Numéro d'appartement</label>
                <input type="text" class="form-control border-r shadow-none"
                    wire:model="num_appartement">
                @error('num_appartement')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

    </div>
    <br>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" onclick="confirmDeleteAccount({{ Auth::id() }}, @this)">
            Supprimer mon compte
        </button>

        <button type="submit" class="bg">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                wire:loading></span>
            Enregistrer les modifications
            <i class="bi bi-arrow-right-circle-fill"></i>
        </button>
    </div>
</form>

