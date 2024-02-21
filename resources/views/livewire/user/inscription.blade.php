<form wire:submit="inscription">
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


    <div class="form-group">
        <label>Nom et prénom</label>
        <input type="text" class="form-control" wire:model="nom" required>
        @error('nom')
            <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label>Numéro de téléphone</label>
        <input type="tel" class="form-control" wire:model="telephone" required>
        @error('telephone')
            <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label>Adresse email</label>
        <input type="email" class="form-control" wire:model="email" required>
        @error('email')
            <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label>Mot de passe</label>
        <input type="password" class="form-control" wire:model="password" required>
        @error('password')
            <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="row">
        <div class="col-sm-8">
            <div class="form-group">
                <label>Photo de profil</label>
                <input type="file" class="form-control" wire:model="photo" required>
                @error('photo')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4">
            @if ($photo)
            <img src="{{ $photo->temporaryUrl() }}" alt="" class="avatar-inscription">
            @else
                <img src="https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg" alt="" class="avatar-inscription">
            @endif
        </div>
    </div>
    <br>
    <div class="modal-footer">
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn bg-red">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                    wire:loading></span>
                Enregistrer
            </button>
        </div>
    </div>
</form>
