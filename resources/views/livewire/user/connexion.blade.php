<div>
    <form wire:submit="connexion">

        @include('components.alert-livewire')

        <div class="form-group">
            <label>Adresse E-mail</label>
            <input type="email" name="email" class="form-control  @error('email') is-invalid @enderror form-control-ps shadow-none" wire:model="email"
                 placeholder="Enter email">
            @error('email')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="password" class="form-control  @error('password') is-invalid @enderror form-control-ps shadow-none" 
                wire:model="password" placeholder="*****">
            @error('password')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
            <div style="text-align: right;">
                <a href="/forget"  class="link">Mot de passe oublié ?</a>
            </div>
        </div>
        <br>
        
        <br><br>
        <div class="d-flex justify-content-between">
            <div>
                <a href="/inscription"  class="link">Créer un compte</a>
            </div>
            <div>
                <button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium">
                    <span wire:loading >
                        <x-Loading></x-Loading>
                    </span>
                    Connexion
                    <i class="bi bi-arrow-right-circle-fill"></i>
                </button>
            </div>
        </div>

</div>
