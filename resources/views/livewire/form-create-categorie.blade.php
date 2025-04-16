<form wire:submit.prevent="creer">
    <div class="row">
        <!-- Category Information Section -->
        <div class="col-md-4 mb-4">
            <h5>Informations sur la catégorie</h5>
            @include('components.alert-livewire')

            <div class="mb-3">
                <label for="titre" class="form-label">Titre de la catégorie</label>
                <input type="text" wire:model="titre" id="titre" class="form-control" required>
                @error('titre') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="title_en" class="form-label">Titre de la catégorie(Englais)</label>
                <input type="text" wire:model="title_en" id="title_en" class="form-control" required>
                @error('title_en') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="title_ar" class="form-label">Titre de la catégorie(Arabe)</label>
                <input type="text" wire:model="title_ar" id="title_ar" class="form-control" required>
                @error('title_ar') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="pourcentage_gain" class="form-label">Marge de gain (%)</label>
                <input type="number" wire:model="pourcentage_gain" id="pourcentage_gain" class="form-control" min="0" step="0.1" required>
                @error('pourcentage_gain') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" wire:model="description" id="description" rows="3" required></textarea>
                @error('description') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="active" class="form-label">Activer la catégorie</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" wire:model="active" id="active" checked>
                    <label class="form-check-label" for="active">{{ $active ? 'La catégorie est active' : 'La catégorie est inactive' }}</label>
                </div>
            </div>
        </div>

        <!-- Image and Icon Upload Section -->
        <div class="col-md-4 mb-4">
            <div class="div-img-update-categorie mb-3">
                <img src="{{ $photo ? $photo->temporaryUrl() : 'https://www.survivorsuk.org/wp-content/uploads/2017/01/no-image.jpg' }}" alt="Image d'illustration" class="img-fluid">
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label">Image d'illustration</label>
                <input type="file" wire:model="photo" wire:target="photo" id="photo" class="form-control" required>
                @error('photo') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="small_icon" class="form-label">Icône</label>
                <input type="file" wire:model="small_icon" id="small_icon" class="form-control">
                @error('small_icon') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <h5>Ajout des prix de livraison par région</h5>
            @forelse ($list_regions as $region)
                <div class="mb-3">
                    <label for="region_{{ $loop->index }}" class="form-label"><i class="bi bi-geo-alt"></i> {{ $region['nom'] }}</label>
                    <input type="number" wire:model="regions.{{ $region->id }}" class="form-control" placeholder="Prix pour : {{ $region->nom }}" step="0.1">
                </div>
            @empty
                <p>Aucune région ajoutée pour le moment.</p>
            @endforelse
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary me-3">
            <span wire:loading>
                <x-loading></x-loading>
            </span>
            <i class="bi bi-save"></i> &nbsp; Enregistrer
        </button>
        <a href="{{ route('gestion_categorie') }}" class="btn btn-secondary">Annuler</a>
    </div>
</form>
