<form wire:submit.prevent="modifier">
    <div class="row">
        <!-- Flash Messages -->
        @if(session()->has('success'))
            <div class="col-12 mb-3">
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @if(session()->has('error'))
            <div class="col-12 mb-3">
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Category Information Section -->
        <div class="col-md-4 mb-4">
            <div class="mb-3">
                <label for="titre" class="form-label">Titre de la catégorie</label>
                <input type="text" wire:model="titre" id="titre" class="form-control" required>
                @error('titre') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="pourcentage_gain" class="form-label">Marge de gain (%)</label>
                <input type="number" wire:model="pourcentage_gain" id="pourcentage_gain" class="form-control" min="0" step="0.1" required>
                @error('pourcentage_gain') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" wire:model="description" id="description" rows="5" required></textarea>
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

        <!-- Image Upload Section -->
        <div class="col-md-4 mb-4">
            <div class="mb-3">
                <label for="photo" class="form-label">Image d'illustration</label>
                <div class="div-img-update-categorie mb-2">
                    <img src="{{ Storage::url($actu_photo) }}" alt="Illustration" class="img-fluid">
                </div>
                <input type="file" wire:model="photo" wire:target="photo" id="photo" class="form-control" required>
                @error('photo') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="small_icon" class="form-label">Icône</label>
                <input type="file" wire:model="small_icon" id="small_icon" class="form-control">
                <div class="text-center mt-2">
                    @if($apercu_small_icon)
                        <img src="{{ Storage::url($apercu_small_icon) }}" alt="Icône" height="70">
                    @endif
                </div>
            </div>
        </div>

        <!-- Region Pricing Section -->
        <div class="col-md-4 mb-4">
            <h5>Ajout des prix de livraison par région</h5>
            @forelse ($region_prix as $region)
                <div class="mb-3">
                    <label for="region_{{ $loop->index }}" class="form-label"><i class="bi bi-geo-alt"></i> {{ $region['nom'] }}</label>
                    <input type="number" wire:model="region_prix.{{ $loop->index }}.prix" id="region_{{ $loop->index }}" class="form-control" placeholder="{{ $region['nom'] }}">
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
            <i class="bi bi-save"></i> &nbsp; Enregistrer les modifications
        </button>
        <a href="{{ route('gestion_categorie') }}" class="btn btn-secondary">
            Annuler
        </a>
    </div>
</form>
