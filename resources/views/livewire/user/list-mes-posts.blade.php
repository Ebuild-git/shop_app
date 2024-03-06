<div class="border border-1 p-3 card rounded">
    <div>
        <div class="d-flex justify-content-between">
            <div>
                <h5>
                    Mes publicaions
                </h5>
            </div>
            <div>
                <form wire:submit="filtrer">
                    <div class="input-group mb-3">
                        <select class="form-control shadow-none" wire:model="etat">
                            <option value=""></option>
                            <option value="En modération">En modération</option>
                            <option value="Active">Active</option>
                        </select>
                        <input type="date" class="form-control shadow-none" wire:model="date">
                        <div class="input-group-append">
                            <button class="btn bg-red shadow-none" type="submit">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                                    wire:loading></span>
                                <i class="bi bi-binoculars"></i>
                                Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col" style="width: 51px;">#</th>
                <th scope="col">titre</th>
                <th scope="col">Prix</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($posts as $item)
                @php
                    $photo = json_decode($item->photos, true);
                @endphp
                <tr>
                    <th scope="row">
                        <div class="avatar-small-product">
                            <img src="{{ Storage::url($photo[0] ?? '') }}" alt="avtar">
                        </div>
                    </th>
                    <td>
                        <b>
                            <a href="/post/{{ $item->id }}" class="link">{{ $item->titre }}</a>
                        </b> <br>
                        <span class="small">
                            <i>Publié le {{ $item->created_at }}</i>
                        </span>
                    </td>
                    <td>{{ $item->prix }} Dt</td>
                    <td style="text-align: right;">
                        @if ($item->propositions->count() > 0)
                            <a href="/publication/{{ $item->id }}/propositions">
                                <button class="btn btn-sm btn-dark">
                                    <i class="bi bi-plug-fill"></i>
                                    Propositions ( {{ $item->propositions->count() }} )
                                </button>
                            </a>
                        @endif
                        @if ($item->sell_at == null && $item->verified_at == null)
                            <a href="/publication/{{ $item->id }}/update">
                                <button class="btn btn-sm btn-info">
                                    <i class="bi bi-pencil-square"></i>
                                    Modifer
                                </button>
                            </a>
                            @endif
                            @if ($item->sell_at == null)
                            <button class="btn btn-sm bg-red" wire:click="delete({{ $item->id }})"
                                wire:confirm="Voulez-vous supprimer cette publication ?">
                                <i class="bi bi-trash"></i>
                            </button>
                            @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <th colspan="4">
                        <div class="alert alert-warning">
                            Aucun article trouvé pour ces critères de recherche.
                        </div>
                    </th>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
