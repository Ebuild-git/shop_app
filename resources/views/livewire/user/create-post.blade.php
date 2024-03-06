<form wire:submit="submit" id="my-form">
    <div>
        <h5>Vends ton article</h5>
    </div>
    <div class="card">
        <div class="row  p-3">
            <div class="col-sm-8">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="text"  class="form-control shadow-none" placeholder="Titre de la publication*"
                                wire:model="titre" required>
                            @error('titre')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <input type="number" class="form-control shadow-none"
                                    placeholder="Prix de votre article" required wire:model="prix">
                                <div class="input-group-append">
                                    <span class="input-group-text link">
                                        <b>DH</b>
                                    </span>
                                </div>
                            </div>
                            @error('prix')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Etat de votre article</label>
                    <span class="bold text-danger">*</span> :
                    <input type="radio" wire:model="etat" required value="neuf" id=""> Neuf
                    <input type="radio" wire:model="etat" required value="occasion" id=""> Occasion
                    @error('etat')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Description</label>
                    <span class="bold text-danger">*</span>
                    <textarea wire:model="description" required class="form-control shadow-none" rows="7"></textarea>
                    @error('description')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <select class="form-control shadow-none" wire:model="gouvernorat" required>
                        <option value="">Veuillez selectionner le gouvernorat</option>
                        @foreach ($list_gouvernorat as $item)
                            <option value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    </select>
                    @error('gouvernorat')
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

    <div class="small text-danger">
        -Tous les champs contenant (*) sont obligatoires
    </div>

</div>
<div class="col-sm-12">
    <!-- Affichage des images prévisualisées -->
    <div class="p-3">
        <div class="row">
            @if ($photos)
                @foreach ($photos as $index => $image)
                    <div class="col-sm-2 col-4"  wire:key="{{$loop->index}}">
                        <div class="car-image-upload">
                            <button class="btn btn-danger btn-sm position-absolute" type="button" wire:click="RemoveMe({{$loop->index}})">
                                <i class="bi bi-x-octagon-fill"></i>
                            </button>
                            <img src="{{ $image->temporaryUrl() }}" alt="Preview Image {{ $index }}"
                                class="w-100">
                        </div>
                    </div>
                @endforeach    
            @endif
            <div class="col-sm-2 col-4">
                <div class="no-picture" id="select-pic">
                    <img src="https://cdn-icons-png.flaticon.com/256/6066/6066857.png" class="w-100"
                        alt="" srcset="">
                </div>
            </div>
        </div>
        <input type="file" wire:model="photos" required id="btn-photos" class="d-none" multiple>
        @error('photos')
            <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

</div>

</div>
<br>
<div class="text-muted text-center small">
Veuillez vous rassurer que votre prublication est complete et exact car apres validation vous n'auriez plus la
possibilité de modifer !
</div>
<br>
<div class="modal-footer">
<button type="reset" class="btn btn-secondary disabled">
Effacer
</button>
<button class="btn bg-red" type="submitbutton" id="submit-form">
<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading></span>
@if ($post)
    <i class="bi bi-pencil-square"></i>
    Enregistrer les modifications
@else
    <i class="bi bi-pencil-square"></i>
    Publier mon article
@endif
</button>
</div>

<script>
    document.getElementById('categorie').onchange = function() {
        var selectedCategoryId = this.value;
        $(".sous-cat").hide();
        $(".sous-cat-" + selectedCategoryId).show();
    };

    // click btn-photos when i click in select-pic
    $("#select-pic").click(function() {
        $('#btn-photos').trigger("click");
    });
</script>


</form>
