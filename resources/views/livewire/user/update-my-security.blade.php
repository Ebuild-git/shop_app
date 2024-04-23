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
        <div class="col-sm-12">
            <div class="form-group">
                <label>Mot de passe actuel</label>
                <input type="text" class="form-control shadow-none"
                    @error('old_password') is-invalid @enderror wire:model="old_password" required>
                @error('old_password')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Nouveau mot de passe</label>
                <div class="form-group" style="position: relative;">
                    <input type="password" class="form-control shadow-none" id="password-1"
                        @error('password') is-invalid @enderror wire:model="password" required>
                    <button class="password_show" type="button" onclick="showPassword(1)">
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
        <div class="col-sm-6">
            <div class="form-group">
                <label>Confirmation du mot de passe</label>
                <div class="form-group" style="position: relative;">
                    <input type="password" class="form-control shadow-none" id="password-2"
                        @error('password_confirmation') is-invalid @enderror
                        wire:model="password_confirmation" required>
                    <button class="password_show" type="button" onclick="showPassword(2)">
                        <span class="input-group-text">
                            <i class="bi bi-eye"></i>
                        </span>
                    </button>
                </div>
                @error('password_confirmation')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
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
                Modifier mon mot de passe
                <i class="bi bi-arrow-right-circle-fill"></i>
            </button>
        </div>
    </div>
</form>
