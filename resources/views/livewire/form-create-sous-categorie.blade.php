<div>
    @include('components.alert-livewire')
    <form wire:submit = "save" class="">
        <div>
            <label class="form-label" for="modalAddCardName">Titre</label>
            <input type="text" wire:model="titre" class="form-control" required
                placeholder="Titre de la sous-catégorie" />
            @error('titre')
                <div class="text-danger">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <br>
        <div>
            <label for="" class="mb-2">Propriétés des annonce de cette sous-catégorie </label>

            <div class="row">
                @forelse ($proprietes as $propriete)
                    <div class="col-sm-6">
                        <div class="input-group mb-1">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="checkbox" wire:model='proprios.{{ $propriete->id }}'
                                    aria-label="Checkbox for following text input">
                            </div>
                            <spann class="input-group-text">
                                {{ $propriete->nom }}
                            </spann>
                            <select class="form-control" wire:model='required.{{$propriete->id}}'>
                                <option value=""></option>
                                <option value="Oui">Oui</option>
                                <option value="Non">Non</option>
                            </select>
                        </div>
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
        <div class="text-center">
            <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light">
                <span wire:loading>
                    <x-loading></x-loading>
                </span>
                Enregistrer
            </button>
        </div>
    </form>
</div>