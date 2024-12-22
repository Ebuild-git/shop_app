<form wire:submit.prevent="creer">
    <div class="row">
        <div class="col-sm-4">
            <h5>Informations sur la catégorie</h5>
            @include('components.alert-livewire')
            <label for="">Titre de la catégorie</label>
            <input type="text" wire:model ="titre" class="form-control" requireddd>
            @error('titre')
                <div class="text-danger">
                    {{ $message }}
                </div>
            @enderror
            <div class="mb-3">
                <label for="">Marge de gain ( % ) </label>
                <input type="text" wire:model ="pourcentage_gain" min="0" step="0.1" class="form-control"
                    requireddd>
                @error('pourcentage_gain')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>


            <label for="">description </label>
            <textarea class="form-control" wire:model="description" rows="3" requireddd></textarea>
            @error('description')
                <div class="text-danger">
                    {{ $message }}
                </div>
            @enderror

            <div class="mb-3">
                <label for="active">Activer la catégorie</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" wire:model="active" id="active" checked>
                    <label class="form-check-label" for="active">
                        {{ $active ? 'La catégorie est active' : 'La catégorie est inactive' }}
                    </label>
                </div>
            </div>


        </div>
        <div class="col-sm-4">
            @if ($photo)
                <div class="div-img-update-categorie">
                    <img src="{{ $photo->temporaryUrl() }}" alt="..">
                </div>
            @else
                <div class="div-img-update-categorie">
                    <img src="https://www.survivorsuk.org/wp-content/uploads/2017/01/no-image.jpg" alt="..">
                </div>
            @endif
            <div class="mb-3">
                <label for="">Image d'illustration </label>
                <input type="file" wire:model="photo" wire:target="photo" class="form-control" requireddd>
                @error('photo')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div>
                <label for="">Icône</label>
                <input type="file" class="form-control" wire:model="small_icon">
                @error('small_icon')
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
        <a href="{{route('gestion_categorie')}}" class="btn btn-secondary">
            Annuler
        </a>
    </div>
</form>
