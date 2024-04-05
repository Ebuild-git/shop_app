<form wire:submit.prevent="modifier">
    @include('components.alert-livewire')
    <div class="row">
        <div class="col-sm-4">
            <div class="mb-3">
                <label for="">Titre de la catégorie</label>
                <input type="text" wire:model ="titre" class="form-control" requireddd>
                @error('titre')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="mb-3">
                        <label for="">Marge de gain ( % ) </label>
                        <input type="text" wire:model ="pourcentage_gain" min="0" step="0.1"
                            class="form-control" requireddd>
                        @error('pourcentage_gain')
                            <div class="text-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="">Description </label>
                <textarea class="form-control" wire:model="description" rows="5" requireddd></textarea>
                @error('description')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>

        </div>
        <div class="col-sm-4">
            <div class="mb-3">
                <div class="div-img-update-categorie">
                    <img src="{{ Storage::url($actu_photo) }}" alt="..">
                </div>
                <label for="">Image d'illustration </label>
                <input type="file" wire:model="photo" wire:target="photo" class="form-control" requireddd>
                @error('photo')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="col-sm-4">
            <h5>
                Ajout des prix de livraison par région
            </h5>
            <div class="row">
                @forelse ($region_prix as $region)
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label for="">
                                <i class="bi bi-geo-alt"></i>
                                {{ $region['nom'] }}
                            </label>
                            <input type="number" wire:model="region_prix.{{ $loop->index }}.prix"
                                placeholder="{{ $region['nom'] }}" class="form-control">
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
    <div class="float-end">
        <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light">
            <span wire:loading>
                <x-loading></x-loading>
            </span>
            <i class="bi bi-plus-circle"></i> &nbsp;
            Enregistrer les modifications
        </button>
    </div>

   
</form>
