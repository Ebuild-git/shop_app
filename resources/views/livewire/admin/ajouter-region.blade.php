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
                    <th>Date d'ajout</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($regions as $item)
                    <tr>
                        <td>{{ $item->nom }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td style="text-align: right">
                            <button class="btn btn-sm btn-danger" type="button" wire:confirm="Voulez-vous supprimer ?"
                                wire:click="delete({{ $item->id }})">
                                <i class="bi bi-trash3"></i> &nbsp;
                                Supprimer
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">
                            <div class="alert alert-warning">
                                Aucune région enregistrée pour le moment !
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </form>
</div>
