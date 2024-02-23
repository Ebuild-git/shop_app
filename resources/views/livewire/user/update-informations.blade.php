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
        <div class="col-sm-6">
            <div class="form-group">
                <label>Nom et prénom</label>
                <input type="text" class="form-control shadow-none" @error('name') is-invalid @enderror
                    wire:model="name" required>
                @error('name')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Numéro de téléphone</label>
                <input type="tel" class="form-control shadow-none"
                    @error('telephone') is-invalid @enderror wire:model="telephone" required>
                @error('telephone')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Gouvernorat</label>
                <select class="form-control shadow-none" wire:model="gouvernorat" required>
                    @foreach ($list_gouvernorat as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
                @error('gouvernorat')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Ville</label>
                <input type="text" class="form-control shadow-none" @error('ville') is-invalid @enderror
                    wire:model="ville" required>
                @error('ville')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Adresse</label>
                <input type="test" class="form-control shadow-none" @error('adress') is-invalid @enderror
                    wire:model="adress" required>
                @error('adress')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Adresse email</label>
                <input type="email" class="form-control shadow-none" @error('email') is-invalid @enderror
                    wire:model="email" required>
                @error('email')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>
    <br>
    <h5 class="text-muted">Nouvelle de photo de profil</h5>
    <div class="row">
        <div class="col-sm-8">
            <div class="form-group">
                <label>Photo de profil</label>
                <input type="file" class="form-control shadow-none" @error('avatar') is-invalid @enderror wire:model="avatar" >
                @error('avatar')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4 text-center">
            @if ($avatar)
                <img src="{{ $avatar->temporaryUrl() }}" alt="" class="avatar-inscription">
            @else
                <img src="{{ Storage::url(Auth::user()->avatar) }}" class="avatar-inscription">
            @endif
        </div>
    </div>

    <br><br>
    <div class="d-flex justify-content-between">
        <div>
        </div>
        <div>
            <button type="submit" class="btn bg-red">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                    wire:loading></span>
                Enregistrer les modifications
                <i class="bi bi-arrow-right-circle-fill"></i>
            </button>
        </div>
    </div>
</form>
