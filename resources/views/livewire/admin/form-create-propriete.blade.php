<div>
    @include('components.alert-livewire')
    <form wire:submit="create">
        <div class="mb-3">
            <label class="form-label">Titre</label>
            <input type="text" wire:model="nom" class="form-control @error('nom') is-invalid @enderror"
                required />
            @error('nom')
                <div class="text-danger">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="row">
            <div class="col-6">
                <div class="mb-3">
                    <label class="form-label">Type de la propriété</label>
                    <select wire:model.live="type" class="form-control @error('type') is-invalid @enderror"
                        required>
                        <option value=""></option>
                        <option value="text">Texte</option>
                        <option value="number">nombre</option>
                        <option value="color">Couleur</option>
                        <option value="option">Cases a coché</option>
                    </select>
                    @error('type')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            @if ($typeselected && $typeselected == 'option')
                <div class="col-6">
                    <div class="mb-3">
                        <label class="form-label">Méthode d'affichage</label>
                        <select wire:model="affichage" class="form-control @error('type') is-invalid @enderror"
                            required>
                            <option value=""></option>
                            <option value="case">Case a coché</option>
                            <option value="input">Auto Autcomplete</option>
                        </select>
                        @error('type')
                            <div class="text-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            @endif

        </div>

        @if ($typeselected && $typeselected == 'option')
            <br>
            {{-- @foreach ($optionsCases as $key => $option)
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">
                        <img width="20" height="20" src="https://img.icons8.com/fluency/48/checked-2.png"
                            alt="checked-2" />
                    </span>
                    <input type="text" class="form-control" wire:model="optionsCases.{{ $key }}">
                    <button class="btn btn-sm btn-light" type="button" wire:click="add_option()">
                        <img width="20" height="20" src="https://img.icons8.com/fluency/48/add--v1.png"
                            alt="add--v1" />
                    </button>
                </div>
            @endforeach --}}
            @foreach ($optionsCases as $key => $option)
    <div class="row g-2 align-items-start mb-2">
        <div class="col-sm-4">
            <input type="text" class="form-control" placeholder="Titre (FR)"
                wire:model="optionsCases.{{ $key }}.titre">
            @error("optionsCases.$key.titre")
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-sm-3">
            <input type="text" class="form-control" placeholder="Title (EN)"
                wire:model="optionsCases.{{ $key }}.title_en">
        </div>
        <div class="col-sm-3">
            <input type="text" class="form-control" dir="rtl" placeholder="العنوان (AR)"
                wire:model="optionsCases.{{ $key }}.title_ar">
        </div>
        <div class="col-sm-2 d-flex gap-1">
            <button class="btn btn-sm btn-light" type="button" wire:click="add_option()">
                <img width="20" height="20" src="https://img.icons8.com/fluency/48/add--v1.png" alt="add">
            </button>
            <button class="btn btn-sm btn-light" type="button" wire:click="delete_option({{ $key }})">
                <img width="20" height="20" src="https://img.icons8.com/fluency/20/delete-sign.png" alt="delete">
            </button>
        </div>
    </div>
@endforeach
        @endif
        <br>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">
                <span wire:loading>
                    <x-loading></x-loading>
                </span>
                Enregistrer
            </button>
        </div>
    </form>
</div>
