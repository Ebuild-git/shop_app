<div>
    <form wire:submit="connexion">

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
            <label for="exampleInputEmail1">Adresse E-mail</label>
            <input type="email" class="form-control" wire:model="email" required placeholder="Enter email">
            @error('email')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Mot de passe</label>
            <input type="password" class="form-control" required wire:model="password" placeholder="*****">
            @error('password')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="modal-footer">
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn bg-red">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading></span>
                    Connexion
                </button>
            </div>
        </div>
</form>
</div>
