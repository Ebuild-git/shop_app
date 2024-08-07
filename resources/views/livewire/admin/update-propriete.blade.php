<form wire:submit="update">
    <div class="row ">
        <div class="col-sm-4 ">
            <div>
                @include('components.alert-livewire')
            </div>
            @csrf
            <div class="mb-3">
                <label>Titre de propriété:</label>
                <input type="text " required wire:model="nom" value="{{ $propriete->nom }}" class="form-control">
                @error('nom')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="row">
                <div class="col">
                    <div class="mb-3">
                        <label class="form-label">Type de la propriété</label>
                        <select wire:model.live="typeselected" class="form-control " required>
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
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label">Méthode d'affichage</label>
                            <select wire:model.live="affichage" class="form-control @error('type') is-invalid @enderror"
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
        </div>
        <div class="col-sm-8">
            <hr>
            @if ($typeselected && $typeselected == 'option')
                <div class="row">
                    @foreach ($optionsCases ?? [] as $key => $option)
                        <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">
                                    <img width="20" height="20"
                                        src="https://img.icons8.com/fluency/48/checked-2.png" alt="checked-2" />
                                </span>
                                <input type="text" class="form-control"
                                    wire:model="optionsCases.{{ $key }}">
                                <button class="btn btn-sm btn-light" type="button" wire:click="add_option()">
                                    <img width="20" height="20"
                                        src="https://img.icons8.com/fluency/48/add--v1.png" alt="add--v1" />
                                </button>
                                <button class="btn btn-sm btn-light" type="button"
                                    wire:click="delete_option({{ $key }})">
                                    <img width="20" height="20"
                                        src="https://img.icons8.com/fluency/20/delete-sign.png" alt="delete-sign" />
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            <span wire:loading>
                <x-loading></x-loading>
            </span>
            Enregistrer les modifications
        </button>
    </div>
</form>
