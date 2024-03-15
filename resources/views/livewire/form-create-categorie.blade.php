<form wire:submit="creer">
    
    
    <h5>Créer une catégorie</h5>
    @include('components.alert-livewire')
    <label for="">Titre de la catégorie</label>
    <input type="text" wire:model ="titre" class="form-control" required>
    @error('titre')
        <div class="text-danger">
            {{ $message }}
        </div>
    @enderror
    <div class="row">
        <div class="col-sm-6">
            <label for="">Marge de gain ( % ) </label>
            <input type="text" wire:model ="pourcentage_gain" min="0" step="0.1"
                class="form-control" required>
            @error('pourcentage_gain')
                <div class="text-danger">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-sm-6">
            <label for="">Frais de livraison </label>
            <input type="text" wire:model ="frais_livraison" min="0" step="0.1"
                class="form-control" required>
            @error('frais_livraison')
                <div class="text-danger">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <label for="">description </label>
    <textarea class="form-control" wire:model="description" rows="3" required></textarea>
    @error('description')
        <div class="text-danger">
            {{ $message }}
        </div>
    @enderror

    <label for="">Image d'illustration </label>
    <input type="file" wire:model="photo" wire:target="photo" class="form-control" required>
    @error('photo')
        <div class="text-danger">
            {{ $message }}
        </div>
    @enderror
    <br>
    <div class=" p-2">
        <label for="">Propriétés des annonce de cette catégorie </label>
        <br>
        <div class="row">
            @forelse ($proprietes as $propriete)
        <div class="col-sm-4">
            <input type="checkbox" class="form-check-input" wire:model="proprios.{{ $propriete->id }}" value="{{ $propriete->id }}">
            {{ $propriete->nom }}
        </div>
    @empty
        <p>Aucune propriété trouvée.</p>
    @endforelse
        </div>
        @error('proprios')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    <br>
    <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light">
        <span wire:loading>
            <x-loading></x-loading>
        </span>
        Enregistrer
    </button>
</form>
