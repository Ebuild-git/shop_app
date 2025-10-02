<form wire:submit="submit" id="my-form">


    <div class="header-titre-create">
        <div class="align-self-start ">
            <img src="/icons/icons8-1-100.png" alt="" height="40" width="40" srcset="">
            <span class="h6" class="color my-auto">
                <b>{{ __('choose_images')}}</b>
            </span>
        </div>
    </div>
    <div class="row ">

        <div class="col-sm-2 col-6 mx-auto position-relative">
            @if ($photo1)
                <button type="button" class="btn-danger btn-cancel-image" wire:click="reset_photo1()">
                    <i class="bi bi-x-octagon"></i>
                </button>
            @endif
            <label for="images" class="drop-container" id="pic1">
                @if ($photo1)
                    <img src="{{ $photo1->temporaryUrl() }}" class="preview">
                @else
                    <img width="50" height="50"
                        src="https://img.icons8.com/parakeet-line/50/018d8d/add-image.png" alt="add-image" />
                @endif
                <input type="file" wire:model="photo1" accept="image/*" class="d-none" id="btn-1">
            </label>
            @error('photo1')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>



        <div class="col-sm-2 col-6 mx-auto position-relative">
            @if ($photo2)
                <button type="button" class="btn-danger btn-cancel-image" wire:click="reset_photo2()">
                    <i class="bi bi-x-octagon"></i>
                </button>
            @endif
            <label for="images" class="drop-container" id="pic2">
                @if ($photo2)
                    <img src="{{ $photo2->temporaryUrl() }}" class="preview">
                @else
                    <img width="50" height="50"
                        src="https://img.icons8.com/parakeet-line/50/018d8d/add-image.png" alt="add-image" />
                @endif
                <input type="file" wire:model="photo2" accept="image/*" class="d-none" id="btn-2">
            </label>
            @error('photo2')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>


        <div class="col-sm-2 col-6 mx-auto position-relative">
            @if ($photo3)
                <button type="button" class="btn-danger btn-cancel-image" wire:click="reset_photo3()">
                    <i class="bi bi-x-octagon"></i>
                </button>
            @endif
            <label for="images" class="drop-container" id="pic3">
                @if ($photo3)
                    <img src="{{ $photo3->temporaryUrl() }}" class="preview">
                @else
                    <img width="50" height="50"
                        src="https://img.icons8.com/parakeet-line/50/018d8d/add-image.png" alt="add-image" />
                @endif
                <input type="file" wire:model="photo3" accept="image/*" class="d-none" id="btn-3">
            </label>
            @error('photo3')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>


        <div class="col-sm-2 col-6 mx-auto position-relative">
            @if ($photo4)
                <button type="button" class="btn-danger btn-cancel-image" wire:click="reset_photo4()">
                    <i class="bi bi-x-octagon"></i>
                </button>
            @endif
            <label for="images" class="drop-container" id="pic4">
                @if ($photo4)
                    <img src="{{ $photo4->temporaryUrl() }}" class="preview">
                @else
                    <img width="50" height="50"
                        src="https://img.icons8.com/parakeet-line/50/018d8d/add-image.png" alt="add-image" />
                @endif
                <input type="file" wire:model="photo4" accept="image/*" class="d-none" id="btn-4">
            </label>
            @error('photo4')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>



        <div class="col-sm-2 col-6 mx-auto position-relative">
            @if ($photo5)
                <button type="button" class="btn-danger btn-cancel-image" wire:click="reset_photo5()">
                    <i class="bi bi-x-octagon"></i>
                </button>
            @endif
            <label for="images" class="drop-container" id="pic5">
                @if ($photo5)
                    <img src="{{ $photo5->temporaryUrl() }}" class="preview">
                @else
                    <img width="50" height="50"
                        src="https://img.icons8.com/parakeet-line/50/018d8d/add-image.png" alt="add-image" />
                @endif
                <input type="file" wire:model="photo5" accept="image/*" class="d-none" id="btn-5">
            </label>
            @error('photo5')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>

    </div>


    <br>
    @error('errorphoto')
        <small class="form-text text-danger">{{ $message }}</small>
    @enderror
    <br>



    <div class="header-titre-create">
        <div class="align-self-start ">
            <img src="/icons/icons8-2-100.png" alt="" height="40" width="40" srcset="">
            <span class="h6" class="color my-auto">
                <b>{{ __('add_more_details')}}</b>
            </span>
        </div>
    </div>


    <div class="row ">
        <div class="col-sm-8">
            <div class="row">
                <div class="col-sm-6">
                    <label>{{ __('post_title')}}</label>
                    <span class="bold text-danger">*</span>
                    <div class="form-group">
                        <input type="text" class="form-control cusor border-r " placeholder="{{ __('placeholder_title') }}"
                            wire:model="titre" required>
                        @error('titre')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <label>{{ __('price1')}}</label>
                    <span class="bold text-danger">*</span>
                    <div class="form-group">
                        <input type="number" class="form-control cusor border-r" placeholder="{{ __('placeholder_price') }}" required
                            wire:model.live="prix">
                        @error('prix')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <label>{{ __('condition')}}</label>
                    <span class="bold text-danger">*</span>
                    <div class="form-group">
                        <select name="etat" wire:model="etat" class="form-control cusor border-r" required>
                            <option value="">{{ __('select_condition') }}</option>
                            <option value="Neuf avec étiquettes">{{ __('Neuf avec étiquettes')}}</option>
                            <option value="Neuf sans étiquettes">{{ __('Neuf sans étiquettes')}}</option>
                            <option value="Très bon état">{{ __('Très bon état')}}</option>
                            <option value="Bon état">{{ __('Bon état')}}</option>
                            <option value="Usé">{{ __('Usé')}}</option>
                        </select>
                        @error('etat')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6">
                    <label>{{ __('original_price') }}</label>
                    <div class="form-group">
                        <input type="number" class="form-control cusor border-r " placeholder="{{ __('placeholder_initial_price') }}"
                            wire:model.live="prix_achat">
                        @error('prix_achat')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">

            <div class="form-group ">
                <label>{{__('Région')}}</label>
                <span class="bold text-danger">*</span>
                <div class="position-relative">
                    <i class="bi bi-globe-europe-africa" style="position: absolute;left: 10px;top: 15px"></i>
                    <select class="form-control cusor border-r pl-4" wire:model.live="region" required
                        style="">
                        <option value="">{{ __('select_region1') }}</option>
                        @foreach ($regions as $item)
                            <option value="{{ $item->id }}">
                                {{ __($item->nom) }}
                            </option>
                        @endforeach
                    </select>
                    @error('region')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label>{{__('Catégorie')}}</label>
                <span class="bold text-danger">*</span>
                <select class="form-control cusor border-r" id="select2-dropdown" wire:model.live="selectedCategory">
                    <option selected value="x">{{ __('select_category') }}</option>
                    @foreach ($categories as $category => $categorie)
                        <option value="{{ $categorie->id }}">
                            @switch(app()->getLocale())
                                @case('en')
                                    {{ $categorie->title_en }}
                                    @break
                                @case('ar')
                                    {{ $categorie->title_ar }}
                                    @break
                                @default
                                    {{ $categorie->titre }}
                            @endswitch
                            @if ($categorie->luxury == 1)
                                <span class="luxury">
                                    ({{ __('luxury')}})
                                </span>
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('selectedCategory')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>


            @if ($selectedCategory)
                <div class="form-group">
                    <label>{{ __('subcategory')}}</label>
                    <span class="bold text-danger">*</span>
                    <select class="form-control cusor border-r" wire:model.live="selectedSubcategory">
                        <option selected value="x">{{ __('select_subcategory')}}</option>
                        @foreach ($sous_categories as $sous)
                            <option value="{{ $sous->id }}">
                                @switch(app()->getLocale())
                                @case('en')
                                    {{ $sous->title_en }}
                                    @break
                                @case('ar')
                                    {{ $sous->title_ar }}
                                    @break
                                @default
                                    {{ $sous->titre}}
                                @endswitch
                            </option>
                        @endforeach
                    </select>
                    @error('selectedSubcategory')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            @endif

            @if ($proprietes && $selectedCategory)
                <div class="row">
                    @foreach ($proprietes as $propriete)
                        @php
                            $propriete_info = DB::table('proprietes')->find($propriete);
                        @endphp
                        @if ($propriete_info)
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="text-capitalize">
                                        {{ __($propriete_info->nom) }}
                                    </label>

                                    @php
                                        $requi = false;
                                        if ($required) {
                                            $collection = collect(json_decode($required ?? [], true));
                                            $requiredStatus =
                                                $collection->firstWhere('id', $propriete_info->id) ?? 'null';
                                            if ($requiredStatus['required'] == 'Oui') {
                                                $requi = true;
                                            }
                                        }
                                    @endphp
                                    @if ($requi)
                                        <span class="bold text-danger">*</span>
                                    @endif

                                    @if ($propriete_info->type == 'option')
                                        @if ($propriete_info->affichage == 'case')
                                            <select wire:model="article_propriete.{{ $propriete_info->nom }}"
                                                @required($requi)
                                                class="form-control cusor border-r option-{{ str_replace(' ', '', strtolower($propriete_info->nom)) }}">
                                                <option value="">{{ __('please_select')}}</option>
                                                @foreach (json_decode($propriete_info->options) as $option)
                                                    <option value="{{ $option }}">
                                                        {{ __($option) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error("article_propriete.{$propriete_info->nom}")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        @else
                                            <input type="text"
                                                class="form-control cusor border-r liste option-{{ str_replace(' ', '', strtolower($propriete_info->nom)) }}"
                                                @required($requi) placeholder="{{ $propriete_info->nom }}"
                                                wire:model="article_propriete.{{ $propriete_info->nom }}"
                                                data-suggestions="{{ $propriete_info->options }}"
                                                data-model="{{ $propriete_info->nom }}">
                                            @error("article_propriete.{$propriete_info->nom}")
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        @endif
                                    @elseif($propriete_info->type == 'color')
                                        @if ($selected_color)
                                            @php
                                            $textColor = $selected_color_code === '#FFFFFF' ? '#000000' : $selected_color_code;
                                            @endphp
                                            : <b style="color: {{ $textColor }};">{{ __($selected_color) }}</b>
                                        @endif
                                        <br>
                                        @foreach ($colors as $item)
                                        @php
                                            $isSelected = $selected_color == $item['nom'] ? 'selected-color' : '';
                                        @endphp
                                            @if ($item['nom'] == 'Multicolore')
                                                <button type="button" class="btn-color-create multi-color-btn cusor {{ $isSelected }}"
                                                    wire:click="choose('{{ $item['nom'] }}','{{ $item['code'] }}','{{ $propriete_info->nom }}')">
                                                </button>
                                            @else
                                                <button style="background-color: {{ $item['code'] }};" type="button"
                                                    class="btn-color-create cusor {{ $isSelected }}"
                                                    wire:click="choose('{{ $item['nom'] }}','{{ $item['code'] }}','{{ $propriete_info->nom }}')">
                                                </button>
                                            @endif
                                        @endforeach
                                        @error("article_propriete.{$propriete_info->nom}")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @else
                                        <input type="{{ $propriete_info->type }}" @required($requi)
                                            placeholder="{{ __($propriete_info->nom) }} @if($propriete_info->nom == 'Poids' && $propriete_info->type == 'number') en KG @endif"
                                            class="form-control cusor border-r"
                                            wire:model="article_propriete.{{ $propriete_info->nom }}">
                                        @error("article_propriete.{$propriete_info->nom}")
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
        <br>

    </div>

    <br><br>

    <div class="header-titre-create">
        <div class="align-self-start ">
            <img src="/icons/icons8-3-100.png" alt="" height="40" width="40" srcset="">
            <span class="h6" class="color my-auto">
                <b>{{ __('description')}}</b>
            </span>
        </div>
    </div>
    <div class="form-group">
        <textarea wire:model="description" class="form-control cusor border-r "
            placeholder="{{ __('description_placeholder')}}" rows="7">

        </textarea>
        @error('description')
            <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>

    <br>
    <div class="text-muted text-center">
        {{ __('publication_warning')}}
        <div class=" text-danger">
            - {{ __('required_fields_note')}}
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
        <a href="/publication" class="btn btn-danger">
            <i class="bi bi-x-lg"></i>
             {{ __('Effacer')}}
        </a>
        <button type="button" class="btn btn-info" wire:click="preview">
            <i class="bi bi-eye"></i>
            {{ __('Aperçu')}}
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
            height: 100px;
            padding: 5px;
            border-radius: 10px;
            border: 2px dashed #018d8d;
            color: #444;
            cursor: pointer;
            transition: background .2s ease-in-out, border .2s ease-in-out;
            overflow: hidden;
        }

        .drop-container .preview {
            height: 100%;
            border-radius: 10px;
            object-fit: cover
        }

        .drop-container .preview:hover {
            transform: scale(1.1)
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

        .btn-cancel-image {
            border: none;
            border-radius: 10px;
            position: absolute;
            left: 25px;
            top: 10px;
            z-index: 9999999999;
        }

        .btn-color-create {
            border-radius: 100%;
            border: none;
            height: 25px !important;
            width: 25px !important;
            border: solid 1px #011d1d8c;
        }
        .selected-color {
            border: 2px solid #000000;
            border-radius: 60%;
            /* padding: 1px; */
        }

    </style>


    <script>
        $(document).ready(function() {
            let suggestions, model;

            $(document).on("keyup", ".liste", function(event) {
                const inputField = $(this);
                if (!suggestions) {
                    suggestions = inputField.data('suggestions');
                    model = inputField.data('model');
                }
                function updateSuggestions(input) {
                    return suggestions.filter(suggestion =>
                        suggestion.toLowerCase().includes(input.toLowerCase())
                    );
                }
                function showSuggestions(suggestions) {
                    const suggestionList = $('<ul id="suggestion-list"></ul>');

                    suggestions.forEach(suggestion => {
                        const listItem = $('<li></li>').text(suggestion);
                        suggestionList.append(listItem);
                    });
                    $('#suggestion-list').remove();
                    inputField.parent().append(suggestionList);
                    suggestionList.on('click', 'li', function() {
                        inputField.val($(this).text());
                        suggestionList.remove();


                        Livewire.dispatch('suggestionSelected', {
                            name: 'marque',
                            value: $(this).text()
                        });


                    });
                }
                const input = inputField.val();
                const filteredSuggestions = updateSuggestions(input);
                showSuggestions(filteredSuggestions);
                $(document).on('click', function(event) {
                    if (!$(event.target).closest(inputField).length && !$(event.target).closest(
                            '#suggestion-list').length) {
                        $('#suggestion-list').remove();
                    }
                });
            });
        });

        $(document).ready(function() {

            //photo 1
            document.getElementById("pic1").addEventListener("click", function() {
                document.getElementById("btn-1").click();
            });

            //photo 2
            document.getElementById("pic2").addEventListener("click", function() {
                document.getElementById("btn-2").click();
            });

            //photo 3
            document.getElementById("pic3").addEventListener("click", function() {
                document.getElementById("btn-3").click();
            });

            //photo 4
            document.getElementById("pic4").addEventListener("click", function() {
                document.getElementById("btn-4").click();
            });

            //photo 5
            document.getElementById("pic5").addEventListener("click", function() {
                document.getElementById("btn-5").click();
            });


        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function handleInputChange() {
                const tailleInputs = document.querySelectorAll('.option-taille, .option-tailleenchiffre, .option-taillebébé, .option-tailleenfant');
                tailleInputs.forEach(input => {
                    input.addEventListener('input', function() {
                        tailleInputs.forEach(otherInput => {
                            if (otherInput !== input) {
                                otherInput.disabled = this.value.trim() !== '';
                            }
                        });
                    });
                });
            }
            function observeDOM() {
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'childList') {
                            handleInputChange();
                        }
                    });
                });
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
            }

            observeDOM();
            handleInputChange();
        });
    </script>



    <!-- Modal pour voir la liste des motifs d'un post réfuser -->
    <div class="modal fade" id="modal_motifs_preview_post" tabindex="1" role="dialog"
        aria-labelledby="UpdatePrice" aria-hidden="true" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
        <div class="modal-dialog modal-lg login-pop-form" role="document">
            <div class="modal-content" id="UpdatePrice">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    @if ($data_post)
                        <div class="row">
                            <div class="col-sm-6">
                                <div>
                                    <div>
                                        <div>
                                            <img src="{{ Storage::url($data_post['photos'][0]) }}" alt=""
                                                style="width: 100% !important;">
                                        </div>
                                        <div class="p-2 d-flex justify-content-start">
                                            @foreach ($data_post['photos'] as $photo)
                                                <div class="gallery-image-details-preview cusor"
                                                    onclick="change_principal_image('{{ Storage::url($photo) }}')">
                                                    <img src="{{ Storage::url($photo) }}" alt=""
                                                        style="width: 100% !important;">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div>
                                    <div>
                                        @if ($data_post['categorie'])
                                            @if ($data_post['categorie']->luxury == 1)
                                                <span class="text-success bg-light-success rounded strong px-2 py-1 d-inline-block">
                                                    <i class="bi bi-gem"></i>
                                                    {{ __('SHOPIN_LUXURY')}}
                                                </span>
                                            @endif

                                            <span class="bg-light-info rounded strong px-2 py-1 d-inline-block" style="background-color: #0080802d; margin-left: 5px;">
                                                <span class="color">

                                                    @switch(app()->getLocale())
                                                        @case('en')
                                                            {{ $data_post['categorie']->title_en ?? ''  }}
                                                            @break
                                                        @case('ar')
                                                            {{ $data_post['categorie']->title_ar ?? '' }}
                                                            @break
                                                        @default
                                                            {{ $data_post['categorie']->titre ?? '' }}
                                                    @endswitch
                                                </span>
                                            </span>

                                            <span class="bg-light-info rounded strong px-2 py-1 d-inline-block mt-1" style="background-color: #ecedf1; margin-left: 5px;">
                                                <span style="color: black;">

                                                    @switch(app()->getLocale())
                                                        @case('en')
                                                            {{ $data_post['sous_categorie']->title_en ?? ''  }}
                                                            @break
                                                        @case('ar')
                                                            {{ $data_post['sous_categorie']->title_ar ?? '' }}
                                                            @break
                                                        @default
                                                            {{ $data_post['sous_categorie']->titre ?? '' }}
                                                    @endswitch
                                                </span>
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class=" mb-1 mt-2 text-capitalize">
                                        {{ $data_post['titre'] }}
                                    </h3>
                                    <div>
                                        <span class="ft-bold color strong fs-lg">
                                            {{ $data_post['prix'] }} <sup>{{ __('currency') }}</sup>
                                        </span>
                                        <span class="badge-frais-details">
                                            <img width="25" height="25"
                                                src="https://img.icons8.com/laces/25/018d8d/delivery.png"
                                                alt="delivery" />
                                            + {{ __('Frais de Livraison')}}
                                        </span>
                                    </div>
                                    <div class="mt-3">
                                        <b class="text-black h6">{{ __('Détails')}}</b>
                                        <table>
                                            <tr>
                                                <td style="min-width: 130px">{{ __('Condition')}}</td>
                                                <td class="text-black space-td">  {{ \App\Traits\TranslateTrait::TranslateText($data_post['etat']) }} </td>
                                            </tr>
                                            <tr>
                                                <td>{{ __('Catégorie')}} </td>
                                                <td class="text-black text-capitalize space-td">
                                                    @switch(app()->getLocale())
                                                        @case('en')
                                                            {{ $data_post['categorie']->title_en ?? ''  }}
                                                            @break
                                                        @case('ar')
                                                            {{ $data_post['categorie']->title_ar ?? '' }}
                                                            @break
                                                        @default
                                                            {{ $data_post['categorie']->titre ?? '' }}
                                                    @endswitch
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{ __('Région')}}</td>
                                                <td class="text-black text-capitalize space-td">
                                                    {{ __($data_post['region']->nom) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{ __('Publié le')}}</td>
                                                <td class="text-black space-td">
                                                    {{ Carbon\Carbon::parse($data_post['created_at'])->format('d/m/Y') }}
                                                </td>
                                            </tr>
                                            @foreach ($data_post['proprietes'] ?? [] as $key => $value)
                                                <tr>
                                                    <td>{{ __(ucfirst($key)) }} </td>
                                                    <td class="text-black space-td">
                                                        @if ($key == 'Couleur')
                                                            @if ($value == '#000000000')
                                                                <img src="/icons/color-wheel.png" height="20"
                                                                    width="20" alt="multicolor"
                                                                    title="Multi color" srcset="">
                                                            @else
                                                                <script>
                                                                    getColorName('{{ $value }}');
                                                                </script>

                                                                <div style="display: flex; align-items: center;">
                                                                    <div style="width: 24px; height: 24px; background-color: {{ $value }}; border-radius: 50%; border: 2px solid #ccc; margin-right: 8px;"></div>
                                                                    <span id="colorName" style="color: #333; font-weight: bold;"></span>
                                                                </div>
                                                            @endif

                                                        @else
                                                            <span class="text-capitalize">
                                                                {{ __($value) }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <div class="mt-3">
                                        <b class="text-black h6">{{ __('Publié le')}}</b>
                                        <p class="text-capitalize">
                                            @if ($data_post['description'])
                                                {!! $data_post['description'] !!}
                                            @else
                                                <div class="text-muted text-center">
                                                    <i>
                                                        <i class="bi bi-info-circle color"></i>
                                                        {{ __('Aucune description disponible !')}}
                                                    </i>
                                                </div>
                                            @endif

                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-md bg-dark btn-sm text-light fs-md ft-medium" type="button"
                                wire:click="submit" id="submit-form" onclick="this.onclick=null; this.disabled=true;">
                                <span wire:loading>
                                    <x-Loading></x-Loading>
                                </span>
                                <i class="bi bi-send"></i>
                                {{ __('Publier')}}
                            </button>

                        </div>

                    @else
                        <div class="p-2 text-center">
                            <img src="/icons/icons8-preview-58.png" alt="" srcset="">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal pour voir la liste des motifs d'un post réfuser -->


</form>
<script>
    document.addEventListener('livewire:init', function () {
                const publishWarning = @json(__('publish_warning'));

        Livewire.on('remplir-alert', function () {
            Swal.fire({
                title: `<span style='font-size: 20px;'>${publishWarning}</span>`,
                showCloseButton: true,
                showConfirmButton: false,
                iconHtml: '<i class="bi bi-info-circle-fill custom-info-icon"></i>',

            });
        });
    });
</script>
<script>
    window.addEventListener('swal:alert', function(event) {
        Swal.fire({
            icon: event.detail.type,
            title: event.detail.title,
            text: event.detail.text,
            confirmButtonText: 'OK'
        });
    });
</script>
