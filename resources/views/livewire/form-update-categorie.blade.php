<div>
    
    <form wire:submit="modifier">
        @include('components.alert-livewire')
        <br>
        <label for="">Titre de la catégorie </label>
        <input type="text" wire:model ="titre" value="{{ $titre }}" class="form-control" required>
        @error('titre')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
        
    
        <label for="">Description </label>
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
                <label for="">Choisir une nouvelle photo</label>
                <input type="file" wire:model ="photo" value="{{ $photo }}" class="form-control" >
                @error('photo')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <br>
        <div>
            <label for="">
                Frais de livraison par regions
            </label>
            <div class="row">
                @forelse ($region_prix as $region)
                    <div class="col-sm-4">
                        <label for=""> {{ $region['nom'] }}</label>
                        <input type="number" wire:model="region_prix.{{ $loop->index }}.prix" placeholder="{{ $region['nom'] }}" class="form-control">
                    </div>
                @empty
                    
                @endforelse
            </div>
        </div>
        <hr>
        <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light">
            <span wire:loading>
                <x-loading></x-loading>
            </span>
            Enregistrer
        </button>
    </form>
    
</div>