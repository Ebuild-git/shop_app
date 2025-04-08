<div>
    <div class="d-flex justify-content-end">
        <form wire:submit="filtre">
            <div class="input-group mb-3">
                <select wire:model ="categorie" class="form-control">
                    <option value="" selected>Toutes les catégories</option>
                    @foreach ($categories as $item)
                        <option value="{{ $item->id }}">{{ $item->titre }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary" type="submit" id="button-addon2">
                   <span wire:loading>
                   <x-loading ></x-loading>
               </span>
                    <i class="fa-solid fa-filter"></i> &nbsp;
                    Filtrer par catégories
                </button>
            </div>
        </form>
    </div>
    <div class="table-responsive text-nowrap ">
        <table class="table text-capitalize" id="sortable-list">
            <thead class="table-dark">
                <th>Catégorie</th>
                <th>Région</th>
                <th>Prix</th>
                <th>Date</th>
                <td></td>
            </thead>
            <tbody>
                @forelse ($regions_categories as $item)
                    <tr>
                        <td> {{ $item->categorie->titre ?? '/' }} </td>
                        <td> <i class="bi bi-globe-europe-africa"></i> {{ $item->region->nom ?? '/' }} </td>
                        <td> {{ $item->prix ?? '/' }} <sup>{{ __('currency') }}</sup></td>
                        <td> {{ $item->created_at ?? '/' }} </td>
                        <td style="text-align: right">
                            <button class="btn btn-sm btn-danger" type="button"
                                onclick="toggle_confirmation({{ $item->id }})">
                                <i class="bi bi-trash3"></i>
                            </button>
                            <button class="btn btn-sm btn-success d-none" id="confirmBtn{{ $item->id }}"
                                type="button" wire:confirm="Voulez-vous supprimer ?"
                                wire:click="delete({{ $item->id }})">
                                <i class="bi bi-check-circle"></i> &nbsp;
                                confirmer
                            </button>
                        </td>
                    </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="alert alert-warning">
                            Aucune donnée disponible pour l'instant.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        function toggle_confirmation(productId) {
            const confirmBtn = document.getElementById('confirmBtn' + productId);
            if (!confirmBtn.classList.contains('d-none')) {
                confirmBtn.classList.add('d-none');
            } else {
                // Masquer tous les autres boutons de confirmation s'ils sont visibles
                document.querySelectorAll('.confirm-btn').forEach(btn => {
                    if (!btn.classList.contains('d-none')) {
                        btn.classList.add('d-none');
                    }
                });
                confirmBtn.classList.remove('d-none');
            }
        }
    </script>
</div>
