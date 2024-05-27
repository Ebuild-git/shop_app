<div>
    @include('components.alert-livewire')
    <form wire:submit="addRegion">
        <div class="input-group mb-3">
            <input type="text" required wire:model="nom" placeholder="Nom de la région" class="form-control">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> &nbsp;
                Ajouter
            </button>
        </div>
        <div class="alert alert-warning">
            La suppression d'une regions entrainera la suppression des articles en vente dans cette région !
        </div>
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <th>Nom</th>
                    <th>Publictions</th>
                    <th>Date d'ajout</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($regions as $region)
                    <tr>
                        <td>{{ $region->nom }}</td>
                        <td>
                            <span title="{{ $region->getPost->count() }}  publications">
                                <i class="bi bi-megaphone"></i>
                                {{ $region->getPost->count() }}
                            </span>
                        </td>
                        <td>{{ $region->created_at }}</td>
                        <td style="text-align: right">
                            <button class="btn btn-sm btn-danger" type="button"
                                onclick="toggle_confirmation({{ $region->id }})">
                                <i class="bi bi-trash3"></i>
                            </button>
                            <button class="btn btn-sm btn-success d-none" id="confirmBtn{{ $region->id }}"
                                type="button" wire:confirm="Voulez-vous supprimer ?"
                                wire:click="delete({{ $region->id }})">
                                <i class="bi bi-check-circle"></i> &nbsp;
                                confirmer
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            <div class="alert alert-warning">
                                Aucune région enregistrée pour le moment !
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </form>


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
