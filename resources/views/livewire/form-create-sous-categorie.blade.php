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
        <div>
            <label class="form-label" for="modalAddCardName">Titre en anglais</label>
            <input type="text" wire:model="title_en" class="form-control" required
                placeholder="Titre en anglais de la sous-catégorie" />
            @error('title_en')
                <div class="text-danger">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div>
            <label class="form-label" for="modalAddCardName">Titre en arabe</label>
            <input type="text" wire:model="title_ar" class="form-control" required
                placeholder="Titre en arabe de la sous-catégorie" />
            @error('title_ar')
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
                    <div class="col-sm-4">
                        <div class="input-group form-control mb-1">
                            <table class="w-100">
                                <tr>
                                    <td style="width: 30px !important;">
                                        <input class="form-check-input mt-0" type="checkbox" wire:model='proprios.{{ $propriete->id }}'
                                        aria-label="Checkbox for following text input">
                                    </td>
                                    <td>
                                        <span >
                                            {{ $propriete->nom }}
                                        </span>
                                    </td>
                                    <td style="width: 60px !important">
                                        <select aria-placeholder="" class="form-control p-1" style="font-size: 14px;" wire:model='required.{{ $propriete->id }}'>
                                            <option value="">Obligé</option>
                                            <option value="Oui">Oui</option>
                                            <option value="Non">Non</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
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
