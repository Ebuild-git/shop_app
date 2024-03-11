@include('components.alert-livewire')
<form wire:submit = "save">
    <div class="col-12">
        <label class="form-label w-100" for="modalAddCard" >Catégorie</label>
        <div class="input-group input-group-merge" >
            <select  id="id_cat2" disabled class="form-control" required>
                <option value=""> </option>
                @foreach ($categories as $item)
                    <option value="{{ $item->id }}">
                        {{ $item->titre }}
                    </option>
                @endforeach
            </select>
        </div>
        <input type="hiddenn" id="id_cat" wire:model='categorie'>
        @error('categorie')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div>
        <label class="form-label" for="modalAddCardName">Titre</label>
        <input type="text" wire:model="titre" class="form-control" required />
        @error('titre')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div>
        <label class="form-label" for="modalAddCardName">Description</label>
        <textarea class="form-control" wire:model="description" rows="5"></textarea>
        @error('description')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    <br>
    <div class="text-center">
        <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light">
            <x-loading></x-loading>
            Enregistrer
        </button>
    </div>
</form>
