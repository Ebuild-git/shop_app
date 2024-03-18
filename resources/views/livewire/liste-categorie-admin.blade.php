<div class="table-responsive text-nowrap ">
    <table class="table">
        <thead class="table-dark">
            <tr>
                <td></td>
                <th>Titre</th>
                <th>sous-catégories</th>
                <th>Propriétés</th>
                <th>Frais</th>
                <th></th>
            </tr>
        </thead>

        <tbody class="table-border-bottom-0">
            @forelse ($liste as $item)
                <tr>
                    <td>
                        <img src="{{ Storage::url($item->icon) }}" alt="{{ $item->icon }}"
                            style="height: 30px !important">
                    </td>
                    <td>
                        <span class="fw-medium text-capitalize">
                            @if ($item->luxury == 1)
                                <span style="color: #008080;">
                                    <b>[ <i class="ti ti-star me-1"></i> LUXURY ]</b>
                                </span>
                            @endif
                            {{ $item->titre }} <br>
                            <span class="small text-muted">
                                <i>Créer le {{ $item->created_at }} </i>
                            </span>
                        </span>
                    </td>
                    <td>
                        {{ $item->getSousCategories->count() }}
                    </td>
                    <td>
                        @php
                            $proprietes = json_decode($item->proprietes, true);
                        @endphp
                        @foreach ($proprietes as $pro)
                            @php
                                $pro = DB::table('proprietes')->find($pro);
                            @endphp
                            @if ($pro)
                                <span class="alert alert-warning p-1 text-capitalize " style="margin: 2px">
                                    {{ $pro->nom }}
                                </span>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        Livraison : {{ $item->frais_livraison }} DH <br>
                        Gain : {{ $item->pourcentage_gain }}
                    </td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item"  href="/admin/add_sous_categorie/{{ $item->id }}">
                                    <i class="ti ti-plus me-1"></i>
                                    Ajouter une sous-catégorie
                                </a>
                                <a class="dropdown-item"  href="javascript:void(0);" wire:click='add_luxury({{ $item->id }})'>
                                    <i class="ti ti-star me-1"></i>
                                    Marquer en tant que LUXURY
                                </a>
                                <a class="dropdown-item" data-bs-toggle="modal"
                                    data-bs-target="#modalToggle-{{ $item->id }}" href="javascript:void(0);"><i
                                        class="ti ti-pencil me-1"></i> Modifier</a>
                                <a class="dropdown-item text-danger" href="javascript:void(0)"
                                    wire:confirm="Voullez vous supprimer ?" wire:click="delete( {{ $item->id }})">
                                    <i class="ti ti-trash me-1"></i> Supprimer </a>
                            </div>
                        </div>
                    </td>
                </tr>
                @include('Admin.categories.modal-update', ['item' => $item])
            @empty
                <tr>
                    <td colspan="6">
                        No Data Found!
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>










    <script>
        function create_sous_cat(id_cat) {
            //select option in select where value is id_cat
            document.getElementById("id_cat").value = id_cat;
            document.getElementById("id_cat2").value = id_cat;
            $("#addsouscategorie").modal("show");
        }
    </script>
</div>
