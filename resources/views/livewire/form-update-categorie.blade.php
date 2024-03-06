<div>
    <form wire:submit="modifier">
        @csrf
        @if (session()->has('error-modal'))
            <span class="text-danger small">
                {{ session('error-modal') }}
            </span>
        @enderror
        @if (session()->has('success-modal'))
            <span class="text-success small">
                {{ session('success-modal') }}
            </span>
        @enderror
        <br>
        <label for="">Titre de la catégorie </label>
        <input type="text" wire:model ="titre" value="{{ $titre }}" class="form-control" required>
        @error('titre')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
    
        <label for="">description </label>
        <textarea class="form-control" wire:model="description" rows="4" required>
            {{ $description }}
        </textarea>
        @error('description')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
    
        <div class="row">
            <div class="col">
                <label for="">Pourcentage de gain</label>
                <input type="number" step="0.1" min="0" wire:model ="pourcentage_gain"
                    value="{{ $pourcentage_gain }}" class="form-control" required>
                @error('pourcentage_gain')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col">
                <label for="">Frais de livraison </label>
                <input type="number" step="0.1" min="0" wire:model ="frais_livraison"
                    value="{{ $frais_livraison }}" class="form-control" required>
                @error('frais_livraison')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <hr>
        <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light">
            <x-loading></x-loading>
            Enregistrer
        </button>
    </form>
    
</div>