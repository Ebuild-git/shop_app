<form wire:submit="update">

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


    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>Nom </label>
                <span class="text-danger">*</span>
                <input type="text" class="form-control shadow-none" @error('lastname') is-invalid @enderror
                    wire:model="lastname" required>
                @error('lastname')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Prénom </label>
                <span class="text-danger">*</span>
                <input type="text" class="form-control shadow-none" @error('firstname') is-invalid @enderror
                    wire:model="firstname" required>
                @error('firstname')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Pseudonyme </label>
                <span class="text-danger">*</span>
                <input type="text" class="form-control shadow-none"
                    @error('username') is-invalid @enderror wire:model="username" required>
                @error('username')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Numéro de téléphone</label>
                <span class="text-danger">*</span>
                <input type="tel" class="form-control shadow-none"
                    @error('phone_number') is-invalid @enderror wire:model="phone_number" required>
                @error('phone_number')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Région</label>
                <span class="text-danger">*</span>
                <select class="form-control shadow-none" wire:model="region" required>
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
                <label>Adresse</label>
                <input type="test" class="form-control shadow-none" @error('address') is-invalid @enderror
                    wire:model="address">
                @error('address')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Adresse email</label>
                <span class="text-danger">*</span>
                <input type="email" class="form-control shadow-none" @error('email') is-invalid @enderror
                    wire:model="email" required>
                @error('email')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4">
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
    </div>
    <br>
    <div class="modal-footer">
        <button type="submit" class="btn bg-red">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                wire:loading></span>
            Enregistrer les modifications
            <i class="bi bi-arrow-right-circle-fill"></i>
        </button>
    </div>
</form>
