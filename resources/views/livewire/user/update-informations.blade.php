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
                <input type="text" class="form-control shadow-none" @error('name') is-invalid @enderror
                    wire:model="name" required>
                @error('name')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Prénom </label>
                <input type="text" class="form-control shadow-none" @error('prenom') is-invalid @enderror
                    wire:model="prenom" required>
                @error('prenom')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Pseudonyme </label>
                <input type="text" class="form-control shadow-none" @error('username') is-invalid @enderror
                    wire:model="username" required>
                @error('username')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Numéro de téléphone</label>
                <input type="tel" class="form-control shadow-none"
                    @error('telephone') is-invalid @enderror wire:model="telephone" required>
                @error('telephone')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Région</label>
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
                <input type="test" class="form-control shadow-none" @error('adress') is-invalid @enderror
                    wire:model="adress" required>
                @error('adress')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4">
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
    <div class="modal-footer">
        <button type="submit" class="btn bg-red">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                wire:loading></span>
            Enregistrer les modifications
            <i class="bi bi-arrow-right-circle-fill"></i>
        </button>
    </div>
</form>
