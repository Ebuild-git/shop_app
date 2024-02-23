<div>
    @if ($propositions->where("etat","refusé")->count() > 0)
    <div class="d-flex justify-content-end">
        <a href="#" class="link" wire:click = "retaurer()">
        <b>Restaurer les autres réfusées</b>
        ( {{ $propositions->where("etat","refusé")->count() }} )
        </a>
    </div>
    <br>
    @endif
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Acheteur</th>
                <th scope="col">Date</th>
                <th scope="col"></th>
            </tr>
        </thead>
        @forelse ($propositions->where("etat","traitement") as $item)
            <tr>
                <th scope="row" style="width: 51px">
                    <div class="avatar-small-product">
                        <img src="{{ Storage::url($item->acheteur->avatar) }}" alt="avtar">
                    </div>
                </th>
                <td>
                    <b>
                        <a href="/user/{{ $item->acheteur->id }}" class="link">{{ $item->acheteur->name }}</a>
                    </b> <br>
                    <span class="small">
                        <i>Membre dépuis {{ $item->acheteur->created_at }}</i>
                    </span>
                </td>
                <td>
                    {{ date('d/m/Y', strtotime($item->created_at)) }}
                </td>
                <td style="text-align: right">
                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                        <button type="button" class="btn btn-danger" wire:click="supprimer({{ $item->acheteur->id }})">
                            Réfuser l'offre
                        </button>
                        <button type="button" class="btn btn-success" wire:click="accepter({{ $item->acheteur->id }})">
                            Accepter l'offre
                        </button>
                    </div>
                </td>
            </tr>
        @empty
        @endforelse
    </table>
</div>
