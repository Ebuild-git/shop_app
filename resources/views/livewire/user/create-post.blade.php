<form wire:submit="submit"  id="my-form">
    <div>
        <h5>Vends ton article</h5>
    </div>
    <div class="card">
        <div class="row  p-3">
            <div class="col-sm-8">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="text" class="form-control shadow-none" placeholder="Titre de la publication*"
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
<div class="col-sm-12">
    <div class="multiple-uploader" id="multiple-uploader">
        <div class="mup-msg">
            <span class="mup-main-msg">cliquez pour télécharger des images.</span>
            <span class="mup-msg" id="max-upload-number">Téléchargez jusqu'à 10 images</span>
            <span class="mup-msg">Seules les images sont autorisées .</span>
        </div>
    </div>
    @error('photos')
        <small class="form-text text-danger">{{ $message }}</small>
    @enderror
</div>

</div>
</div>
</div>
<br>
<div class="text-muted text-center small">
VEuillez vous rassurer que votre prublication est complete et exact car apres validation vous n'auriez plus la
possibilité de modifer !
</div>
<br>
<div class="modal-footer">
<button type="reset" class="btn btn-secondary disabled">
Effacer
</button>
<button class="btn bg-red" type="submit" id="submit-form">
<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading></span>
@if ($post)
    <i class="bi bi-pencil-square"></i>
    Enregistrer les modifications
@else
    <i class="bi bi-pencil-square"></i>
    Publier mon article
@endif
</button>
<input type="submit" id="submit" class="d-none"  >
</div>
<link href="/assets-user/picker-image/src/css/main.css" rel="stylesheet" type="text/css">
<script src="/assets-user/picker-image/src/js/multiple-uploader.js"></script>
<script>
    document.getElementById('categorie').onchange = function() {
        var selectedCategoryId = this.value;
        $(".sous-cat").hide();
        $(".sous-cat-" + selectedCategoryId).show();
    };


    let multipleUploader = new MultipleUploader('#multiple-uploader').init({
        maxUpload: 20, // maximum number of uploaded images
        maxSize: 2, // in size in mb
        filesInpName: 'photos', // input name sent to backend
        formSelector: '#my-form', // form selector
    });


    //add attribute on input which name photos when submit-form is clicked before submit form my-form
    $('#submit-form').click(function () {
        //prevent submit form ant set atribute before
        //get name photos[] input 
        var inputPhotos = document.getElementById("photos");
        inputPhotos.setAttribute('wire:model', 'photos');
        //make click
        $("#submit").trigger('click');
        
    });


</script>


</form>
