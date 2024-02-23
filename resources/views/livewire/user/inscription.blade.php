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
        <input type="text" class="form-control shadow-none" @error('nom') is-invalid @enderror wire:model="nom" required>
        @error('nom')
            <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label>Numéro de téléphone</label>
        <input type="tel" class="form-control shadow-none" @error('telephone') is-invalid @enderror wire:model="telephone" required>
        @error('telephone')
            <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label>Adresse email</label>
        <input type="email" class="form-control shadow-none" @error('email') is-invalid @enderror wire:model="email" required>
        @error('email')
            <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label>Mot de passe</label>
        <div class="input-group mb-3">
            <input type="password" class="form-control shadow-none" id="password" wire:model="password" required>
            <div class="input-group-prepend">
                <span class="input-group-text" id="showPassword" >
                    <i class="bi bi-eye"></i>
                </span>
            </div>
        </div>
        @error('password')
            <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="row">
        <div class="col-sm-8">
            <div class="form-group">
                <label>Photo de profil</label>
                <input type="file" class="form-control shadow-none" @error('photo') is-invalid @enderror wire:model="photo" required>
                @error('photo')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4">
            @if ($photo)
                <img src="{{ $photo->temporaryUrl() }}" alt="" class="avatar-inscription">
            @else
                <img src="https://static.vecteezy.com/system/resources/thumbnails/009/292/244/small/default-avatar-icon-of-social-media-user-vector.jpg"
                    alt="" class="avatar-inscription">
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
                <i class="bi bi-arrow-right-circle-fill"></i>
            </button>
        </div>
    </div>


    <script>
        //change type password to text
        document.getElementById("showPassword").addEventListener("click", function () {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        });
    </script>
</form>
