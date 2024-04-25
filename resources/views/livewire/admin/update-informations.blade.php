<div>
    <form wire:submit="update_informations">
        @include('components.alert-livewire')
        <div class="d-flex align-items-start align-items-sm-center gap-4">
            <img src="{{ Storage::url($avatar) }}" alt="user-avatar" class="d-block w-px-100 h-px-100 rounded"
                id="uploadedAvatar" />
            <div class="button-wrapper">
                <label for="upload" class="btn btn-primary me-2 mb-3" tabindex="0">
                    <span class="d-none d-sm-block">photo de profil</span>
                    <i class="ti ti-upload d-block d-sm-none"></i>
                    <input type="file" wire:model="avatar2" id="upload" class="account-file-input" hidden
                        accept="image/png, image/jpeg" />
                </label>
                <button type="button" class="btn btn-label-secondary account-image-reset mb-3">
                    <i class="ti ti-refresh-dot d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Reset</span>
                </button>

                <div class="text-muted">Allowed JPG, GIF or PNG. Max size of 800K</div>
            </div>
            @error('avatar2')
                <small class="text-danger small">{{ $message }}</small>
            @enderror
        </div>


        <div class="row">
            <div class="mb-3 col-md-6">
                <label for="firstName" class="form-label">Nom</label>
                <input class="form-control" type="text" id="firstName" required name="firstName" wire:model="firstname"
                    value="John" autofocus />
                @error('firstname')
                    <small class="text-danger small">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3 col-md-6">
                <label for="email" class="form-label">E-mail</label>
                <input class="form-control" type="text" id="email" required wire:model="email" name="email"
                    value="john.doe@example.com" placeholder="john.doe@example.com" />
                @error('email')
                    <small class="text-danger small">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="mt-2">
            <button type="submit" class="btn btn-primary me-2">
                Enregistrer les changements
            </button>
            <button type="reset" class="btn btn-label-secondary">Annuler</button>
        </div>
    </form>
</div>
