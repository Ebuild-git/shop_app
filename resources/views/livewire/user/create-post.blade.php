<form wire:submit="submit">

    <div class="row  p-3">
        <div class="d-flex justify-content-between col-12 bg-red p-3 rounded mb-2">
            <div>
                <h5>Nouvelle publication</h5>
            </div>
            <div>
                <button class="btn bg-red" type="submit">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading></span>
                    @if ($post)
                        <i class="bi bi-pencil-square"></i>
                        Enregistrer les modifications
                    @else
                        <i class="bi bi-pencil-square"></i>
                        Publier
                    @endif
                </button>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="form-group">
                <label for="exampleInputEmail1">Titre de la publication</label>
                <span class="bold text-danger">*</span>
                <input type="text" class="form-control shadow-none" wire:model="titre" required>
                @error('titre')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">description</label>
                <span class="bold text-danger">*</span>
                <textarea wire:model="description" required class="form-control shadow-none" rows="10"></textarea>
                @error('titre')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Ville </label>
                        <span class="bold text-danger">*</span>
                        <input type="text" class="form-control shadow-none" wire:model="ville" required>
                        @error('ville')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="exampleInputEmail1">gouvernorat</label>
                        <span class="bold text-danger">*</span>
                        <select class="form-control shadow-none" wire:model="gouvernorat" required>
                            @foreach ($list_gouvernorat as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                        @error('gouvernorat')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="exampleInputEmail1">Prix</label>
                <span class="bold text-danger">*</span>
                <div class="input-group mb-3">
                    <input type="number" class="form-control shadow-none" wire:model="prix">
                    <div class="input-group-append">
                        <span class="input-group-text">DT</span>
                    </div>
                </div>
                @error('prix')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Catégorie</label>
                <span class="bold text-danger">*</span>
                <select class="form-control shadow-none" wire:model="categorie" id="categorie">
                    <option selected>Veuilez selectionner une catégorie</option>
                    @foreach ($categories as $categorie)
                        <option value="{{ $categorie->id }}">
                            {{ $categorie->titre }}
                        </option>
                    @endforeach
                </select>
                @error('categorie')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Sous-catégorie</label>
                <span class="bold text-danger">*</span>
                <select class="form-control shadow-none" wire:model="id_sous_categorie">
                    <option selected>Veuilez selectionner une sous-catégorie</option>
                    @foreach ($sous_categories as $sous)
                        <option value="{{ $sous->id }}" class="sous-cat sous-cat-{{ $sous->id_categorie }}">
                            {{ $sous->titre }}
                        </option>
                    @endforeach
                </select>
                @error('id_sous_categorie')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Images</label>
                <span class="bold text-danger">*</span>
                <input type="file" multiple class="form-control shadow-none" wire:model="photos" @required(!$post)>
                @error('photos')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            @if ($old_photos)
                <div class="row">
                    @foreach ($old_photos as $item)
                        <div class="col-6 ">
                            <div class="card-iamge-post-create">
                                <img src="{{ Storage::url($item) }}">
                                <button class="btn btn-sm btn-danger" type="button"
                                    wire:click="removeOldPhoto('{{ $item }}',{{ $post->id }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="small text-danger">
                -Tous les champs contenant (*) sont obligatoires
            </div>
            <br><br>
            <div>
                @if (session()->has('error'))
                    <div class="alert alert-danger small text-center">
                        {{ session('error') }}
                    </div>
                    <br>
                @enderror
                @if (session()->has('info'))
                    <div class="alert alert-info small text-center">
                        {{ session('info') }}
                    </div>
                    <br>
                @enderror
                @if (session()->has('success'))
                    <div class="alert alert-success small text-center">
                        {{ session('success') }}
                    </div>
                    <br>
                @enderror
</div>
</div>
</div>

<script>
    document.getElementById('categorie').onchange = function() {
        var selectedCategoryId = this.value;
        $(".sous-cat").hide();
        $(".sous-cat-" + selectedCategoryId).show();
    };
</script>

</form>
