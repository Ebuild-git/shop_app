<form wire:submit.prevent="creer">
    <div class="row">
        <div class="col-sm-8">
            <h5>Informations sur la catégorie</h5>
            @include('components.alert-livewire')
            <label for="">Titre de la catégorie</label>
            <input type="text" wire:model ="titre" class="form-control" requireddd>
            @error('titre')
                <div class="text-danger">
                    {{ $message }}
                </div>
            @enderror
            <div class="row">
                <div class="col-sm-6">
                    <label for="">Marge de gain ( % ) </label>
                    <input type="text" wire:model ="pourcentage_gain" min="0" step="0.1"
                        class="form-control" requireddd>
                    @error('pourcentage_gain')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-sm-6">
                    <label for="">Image d'illustration </label>
                    <input type="file" wire:model="photo" wire:target="photo" class="form-control" requireddd>
                    @error('photo')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <label for="">description </label>
            <textarea class="form-control" wire:model="description" rows="3" requireddd></textarea>
            @error('description')
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
                            <input type="checkbox" class="form-check-input" wire:model="proprios.{{ $propriete->id }}"
                                value="{{ $propriete->id }}">
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
        </div>
        <div class="col-sm-4">
            <h5>
                Ajout des prix de livraison par région
            </h5>
            @forelse ($list_regions as $region)
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <i class="bi bi-geo-alt"></i>
                        {{ $region->nom }}
                    </span>
                    <input type="number" class="form-control" wire:model="regions.{{ $region->id }}"
                        placeholder="prix pour : {{ $region->nom }}" step="0.1">
                </div>
            @empty
            @endforelse
        </div>
    </div>
    <div class="float-end">
        <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light">
            <span wire:loading>
                <x-loading></x-loading>
            </span>
            <i class="bi bi-plus-circle"></i> &nbsp;
            Enregistrer
        </button>
    </div>
</form>
