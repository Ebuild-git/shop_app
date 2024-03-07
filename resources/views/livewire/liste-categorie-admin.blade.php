<div class="table-responsive text-nowrap ">
    <table class="table">
        <thead class="table-dark">
            <tr>
                <td></td>
                <td></td>
                <th>Titre</th>
                <th>sous-catégories</th>
                <th>Publications</th>
                <th>Frais</th>
                <th></th>
            </tr>
        </thead>

        <tbody class="table-border-bottom-0">
            @forelse ($liste as $item)
                <tr>
                    <td></td>
                    <td>
                        <img src="{{ Storage::url($item->icon) }}" alt="{{ $item->icon }}"
                            style="height: 30px !important">
                    </td>
                    <td>
                        <span class="fw-medium">
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
                        {{ $item->getPost->count() }}
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
                                <a class="dropdown-item" data-bs-toggle="modal"
                                data-bs-target="#modalToggleajouter-{{ $item->id }}" href="javascript:void(0);"><i
                                    class="ti ti-pencil me-1"></i>Ajouter une sous-catégorie</a>

                                <a class="dropdown-item" data-bs-toggle="modal"
                                    data-bs-target="#modalToggle-{{ $item->id }}" href="javascript:void(0);"><i
                                        class="ti ti-pencil me-1"></i> Modifier</a>
                                <a class="dropdown-item" href="javascript:void(0)"
                                    wire:click="delete( {{ $item->id }})">
                                    <i class="ti ti-trash me-1"></i> Supprimer </a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="7">
                        <table class="">
                            @foreach ($item->getSousCategories as $sous)
                            <tr>
                                <td>{{ $sous->titre }}</td>
                            </tr>
                        @endforeach
                        </table>
                    </td>
                </tr>
                @include('Admin.categories.modal-update', ['item' => $item])
            @empty
                <tr>
                    <td colspan="7">
                        No Data Found!
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
