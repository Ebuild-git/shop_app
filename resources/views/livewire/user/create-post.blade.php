<form wire:submit="submit" id="my-form">
    <div class="row p-3">
        <div class="col-sm-8">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <input type="text" class="form-control " placeholder="Titre de la publication*"
                            wire:model.live="titre" required>
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
                            <option value="Neuf avec étiquettes">Neuf avec étiquettes</option>
                            <option value="Neuf sans étiquettes">Neuf sans étiquettes</option>
                            <option value="Très bon état">Très bon état</option>
                            <option value="Bon état">Bon état</option>
                            <option value="Usé">Usé</option>
                        </select>
                        @error('etat')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <input type="number" class="form-control " placeholder="Prix D'achat : {{ $titre }}"
                            required wire:model.live="prix_achat">
                        @error('prix_achat')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <textarea wire:model="description" required class="form-control " rows="7"
                    placeholder="Veuilez entrer la description de votre article : {{ $titre }}">
                    
                </textarea>
                @error('description')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-4">

            <div class="form-group position-relative">
                <i class="bi bi-globe-europe-africa" style="position: absolute;left: 10px;top: 15px"></i>
                <select class="form-control pl-4" wire:model.live="region" required style="">
                    <option value="">Veuillez selectionner la region</option>
                    @foreach ($regions as $item)
                        <option value="{{ $item->id }}">{{ $item->nom }}</option>
                    @endforeach
                </select>
                @error('region')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <select class="form-control" id="select2-dropdown" wire:model.live="selectedCategory">
                    <option selected value="x">Veuilez selectionner une catégorie*</option>
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
                    <select class="form-control" wire:model.live="selectedSubcategory">
                        <option selected value="x">Veuilez selectionner une sous-catégorie</option>
                        @foreach ($sous_categories as $sous)
                            <option value="{{ $sous->id }}">
                                {{ $sous->titre }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_sous_categorie')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            @endif

            @if ($proprietes)
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
                                    @if ($propriete_info->type == 'option')
                                        <select wire:model="article_propriete.{{ $propriete_info->nom }}"
                                            class="form-control ">
                                            <option value=""></option>
                                            @forelse (json_decode($propriete_info->options) as $option)
                                                <option value="{{ $option }}">{{ $option }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    @else
                                        <input type="{{ $propriete_info->type }}"
                                            placeholder="{{ $propriete_info->nom }}" class="form-control"
                                            wire:model="article_propriete.{{ $propriete_info->nom }}">
                                    @endif
                                </div>
                            </div>
                        @endif
                    @empty
                    @endforelse
                </div>
            @endif
        </div>
        <br>

    </div>



    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2({})
        })
    </script>



    <!-- Affichage des images prévisualisées -->
    <div class="d-flex justify-content-end">
        @if ($photos)
            @foreach ($photos as $index => $image)
                <div class="" wire:key="{{ $loop->index }}">
                    <div class="car-image-upload">
                        <button class=" position-absolute" type="button" wire:click="RemoveMe({{ $loop->index }})">
                            <i class="lni lni-cross-circle"></i>
                        </button>
                        <img src="{{ $image->temporaryUrl() }}" alt="Preview Image {{ $index }}">
                    </div>
                </div>
            @endforeach

        @endif
    </div>


    <label for="images" class="drop-container" id="dropcontainer">
        <span class="drop-title">Veuillez selectionner maximun 4 images</span>
        or
        <input type="file" wire:model="photos" accept="image/*" name="photos" id="btn-photos" multiple>
    </label>

    @error('photos')
        <small class="form-text text-danger">{{ $message }}</small>
    @enderror


    <br>
    <div class="text-muted text-center">
        Veuillez vous rassurer que votre prublication est complete et exact car apres validation vous n'auriez plus la
        possibilité de modifer !
        <div class=" text-danger">
            -Tous les champs contenant (*) sont obligatoires
        </div>
    </div>
    <div>
        @include('components.alert-livewire')
    </div>


    <br>
    <div class="modal-footer">
        <span wire:loading>
            <x-Loading></x-Loading>
        </span>
        @if ($extimation_prix > 0)
            <div>
                Extimation du prix de vente : <b> {{ $extimation_prix }} DH </b>
            </div>
        @endif
        <button type="reset" class="btn btn-secondary disabled">
            Effacer
        </button>
        <button class="btn btn-md bg-dark text-light fs-md ft-medium" type="submitbutton" id="submit-form">
            @if ($post)
                <i class="bi bi-pencil-square"></i>
                Enregistrer les modifications
            @else
                <i class="bi bi-pencil-square"></i>
                Publier mon article
            @endif
        </button>
    </div>



    <style>
        .drop-container {
            position: relative;
            display: flex;
            gap: 10px;
            width: 100% !important;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 200px;
            padding: 20px;
            border-radius: 10px;
            border: 2px dashed #555;
            color: #444;
            cursor: pointer;
            transition: background .2s ease-in-out, border .2s ease-in-out;
        }

        .drop-container:hover {
            background: #eee;
            border-color: #111;
        }

        .drop-container:hover .drop-title {
            color: #222;
        }

        .drop-title {
            color: #444;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            transition: color .2s ease-in-out;
        }

        input[type=file]::file-selector-button {
            margin-right: 20px;
            border: none;
            background: #018d8d;
            padding: 10px 20px;
            border-radius: 10px;
            color: #fff;
            cursor: pointer;
            transition: background .2s ease-in-out;
        }

        input[type=file]::file-selector-button:hover {
            background: #023d3d;
        }
    </style>







</form>
