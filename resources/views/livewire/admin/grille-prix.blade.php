<div>
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
                        <td> {{ $item->prix ?? '/' }} DH</td>
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
                    s
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
