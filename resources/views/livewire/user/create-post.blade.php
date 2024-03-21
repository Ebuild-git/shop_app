<form wire:submit="submit" id="my-form">
    <div>
        <h3>Publier un article</h3>
    </div>
    <div class="">
        <div class="row  p-3">
            <div class="col-sm-8">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="text" class="form-control " placeholder="Titre de la publication*"
                                wire:model="titre" required>
                            @error('titre')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="number" class="form-control " placeholder="Prix de votre article" required
                                wire:model.live="prix">
                            @error('prix')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <select name="etat" wire:model="etat" class="form-control ">
                                <option value="">Veuillez selectionner l'état*</option>
                                <option value="neuf">Neuf</option>
                                <option value="occasion">Occasion</option>
                            </select>
                            @error('etat')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <select class="form-control " wire:model.live="region" required>
                                <option value="">Veuillez selectionner la region</option>
                                @foreach ($regions as $item)
                                    <option value="{{ $item->id }}">{{ $item->nom }}</option>
                                @endforeach
                            </select>
                            @error('region')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Description</label>
                    <span class="bold text-danger">*</span>
                    <textarea wire:model="description" required class="form-control " rows="7"></textarea>
                    @error('description')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

            </div>
            <div class="col-sm-4">

                <div class="form-group">
                    <select class="form-control " wire:model.live="selectedCategory">
                        <option selected>Veuilez selectionner une catégorie*</option>
                        @foreach ($categories as $category => $categorie)
                            <option value="{{ $categorie->id }}">
                                {{ $categorie->titre }}
                            </option>
                        @endforeach
                    </select>
                    @error('categorie')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                @if ($selectedCategory)
                    <div class="form-group">
                        <label for="exampleInputEmail1">Sous-catégorie</label>
                        <span class="bold text-danger">*</span>
                        <select class="form-control" wire:model="id_sous_categorie">
                            <option selected>Veuilez selectionner une sous-catégorie</option>
                            @foreach ($sous_categories as $sous)
                                <option value="{{ $sous->id }}"
                                    class="sous-cat sous-cat-{{ $sous->id_categorie }}">
                                    {{ $sous->titre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_sous_categorie')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="row">
                        @forelse ($proprietes as $propriete)
                            @php
                                $propriete_info = DB::table('proprietes')->find($propriete);
                            @endphp
                            @if ($propriete_info)
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="text-capitalize">
                                            <i class="bi bi-info-circle"></i>
                                            {{ $propriete_info->nom }}
                                        </label>
                                        <input type="{{ $propriete_info->type }}"
                                            placeholder="{{ $propriete_info->nom }}" class="form-control"
                                            wire:model="article_propriete.{{ $propriete_info->nom }}">
                                    </div>
                                </div>
                            @endif
                        @empty
                        @endforelse
                    </div>
                    <br>
                @endif
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
                    @include('components.alert-livewire')
                </div>

                <div class=" text-danger">
                    -Tous les champs contenant (*) sont obligatoires
                </div>

            </div>
            <div class="col-sm-12">
                <!-- Affichage des images prévisualisées -->
                <div class="p-3">
                    <div class="row">
                        @if ($photos)
                            @foreach ($photos as $index => $image)
                                <div class="col-sm-2 col-4" wire:key="{{ $loop->index }}">
                                    <div class="car-image-upload">
                                        <button class="btn btn-danger btn-sm position-absolute" type="button"
                                            wire:click="RemoveMe({{ $loop->index }})">
                                            <i class="lni lni-cross-circle"></i>
                                        </button>
                                        <img src="{{ $image->temporaryUrl() }}"
                                            alt="Preview Image {{ $index }}" class="w-100">
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
    <div class="text-muted text-center">
        Veuillez vous rassurer que votre prublication est complete et exact car apres validation vous n'auriez plus la
        possibilité de modifer !
    </div>
    <br>
    <div class="modal-footer">
        @if ($extimation_prix > 0)
            <div>
                Extimation du prix de vente : <b> {{ $extimation_prix }} DH </b>
            </div>
        @endif
        <button type="reset" class="btn btn-secondary disabled">
            Effacer
        </button>
        <button class="btn btn-md bg-dark text-light fs-md ft-medium" type="submitbutton" id="submit-form">
            <span wire:loading>
                <x-Loading></x-Loading>
            </span>
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
        // click btn-photos when i click in select-pic
        document.getElementById("select-pic").addEventListener("click", function() {
            document.getElementById("btn-photos").click();
        });
    </script>


</form>
