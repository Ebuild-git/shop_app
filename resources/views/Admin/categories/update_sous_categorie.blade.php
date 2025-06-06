@extends('Admin.fixe')
@section('titre', 'Sous Catégories de ' . $sous_categorie->titre)
@section('content')



@section('body')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light ">
                Catégories /
                {{ $sous_categorie->categorie->titre }} /
            </span>
            {{ $sous_categorie->titre }}
        </h4>
        <!-- Bootstrap Table with Header - Dark -->
        <div class="container">
            <div class="row">
                <div class="col-sm-12 card">
                    <div class="p-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5>Sous-catégorie de "{{ $sous_categorie->titre }}"</h5>
                            </div>
                            <div>
                                <a href="/admin/add_sous_categorie/{{ $sous_categorie->categorie->id }}">
                                    <button class="btn btn-dark btn-sm me-sm-3 me-1 waves-effect waves-light">
                                        <i class="bi bi-arrow-left"></i> &nbsp; Retour a la catégorie
                                    </button>
                                </a>
                            </div>
                        </div>
                        @include('components.alert-livewire')
                    </div>
                    <br>
                    <div>
                        <form method="POST" action="{{ route('post.update_sous_categorie') }}">
                            @csrf
                            <input type="hidden" value="{{ $sous_categorie->id }}" name="id">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label>Titre de la sous-catégorie :</label>
                                        <input type="text " required name="titre" value="{{ $sous_categorie->titre }}"
                                            class="form-control">
                                        @error('titre')
                                            <div class="text-danger">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label>Titre en anglais :</label>
                                        <input type="text" required name="title_en" value="{{ $sous_categorie->title_en }}"
                                            class="form-control">
                                        @error('title_en')
                                            <div class="text-danger">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label>Titre en arabe :</label>
                                        <input type="text" required name="title_ar" value="{{ $sous_categorie->title_ar }}"
                                            class="form-control">
                                        @error('title_ar')
                                            <div class="text-danger">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label>Catégorie d'appartenance :</label>
                                        <select name="id_categorie" class="form-control">
                                            <option value=""></option>
                                            @foreach ($categories as $item)
                                                <option value="{{ $item->id }}" @selected($item->id == $sous_categorie->id_categorie)>
                                                    {{ $item->titre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_categorie')
                                            <div class="text-danger">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <label>Propriétés de la sous-catégorie </label>
                                    <div class="row">
                                        @forelse ($mes_proprietes as $propriete)
                                            @php
                                                $requi = false;
                                                $collection = collect(
                                                    json_decode($sous_categorie->required ?? [], true),
                                                );
                                                $requiredStatus =
                                                    $collection->firstWhere('id', $propriete['id']) ?? null;
                                                if ($requiredStatus) {
                                                    if ($requiredStatus['required'] == 'Oui') {
                                                        $requi = true;
                                                    }
                                                }
                                            @endphp


                                            <div class="col-sm-6">
                                                <div class="input-group form-control mb-1">
                                                    <table class="w-100">
                                                        <tr>
                                                            <td style="width: 30px !important;">
                                                                <input class="form-check-input mt-0"
                                                                    @checked($propriete['isChecked']) type="checkbox"
                                                                    name="option[{{ $propriete['id'] }}]"
                                                                    aria-label="Checkbox for following text input"
                                                                    value="{{ $propriete['id'] }}">
                                                            </td>
                                                            <td>
                                                                <span>
                                                                    {{ $propriete['nom'] }}
                                                                </span>
                                                            </td>
                                                            <td style="width: 60px !important">
                                                                <select aria-placeholder=""  name="required[{{ $propriete['id'] }}]" class="form-control p-1"
                                                                    style="font-size: 14px;">
                                                                    <option value="Non">Non</option>
                                                                    <option value="Oui" @selected($requi)>Oui</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        @empty
                                            <p>Aucune propriété trouvée.</p>
                                        @endforelse
                                        @error('option')
                                            <div class="text-danger">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
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
                    </div>

                    <br>
                </div>
            </div>
        </div>
    </div>
    <!--/ Content -->






@endsection

@section('script')
    <script src="/assets-admin/vendor/libs/jquery/jquery.js"></script>
    <script src="/assets-admin/vendor/libs/popper/popper.js"></script>
    <script src="/assets-admin/vendor/js/bootstrap.js"></script>
    <script src="/assets-admin/vendor/libs/node-waves/node-waves.js"></script>
    <script src="/assets-admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="/assets-admin/vendor/libs/hammer/hammer.js"></script>
    <script src="/assets-admin/vendor/libs/i18n/i18n.js"></script>
    <script src="/assets-admin/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="/assets-admin/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="/assets-admin/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="/assets-admin/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>

    <!-- Main JS -->
    <script src="/assets-admin/js/main.js"></script>

    <!-- Page JS -->
    <script src="/assets-admin/js/app-logistics-dashboard.js"></script>

@endsection
