<div>
    @include('components.alert-livewire')
    <form wire:submit="addRegion">
        <div class="input-group mb-3">
            <input type="text" required wire:model="nom" placeholder="Nom de la région" class="form-control">
            <button type="submit" class="btn btn-primary">
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
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($regions as $item)
                    <tr>
                        <td>{{ $item->nom }}</td>
                        <td style="text-align: right">
                            <button class="btn btn-sm btn-danger" type="button" wire:confirm="Voulez-vous supprimer ?"
                                wire:click="delete({{ $item->id }})">
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
