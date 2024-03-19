
<div class="row">
    <div class="col-sm-4">
        <div class="card">
            <div class="carouselpropositions">
                <img src="{{ Storage::url($post->photos[0] ?? '') }}" >
            </div>
            <div class="p-2">
                <h6>
                    {{ $post->titre }}
                </h6>
                <h5 class="color-orange">
                    {{ $post->prix }} DT
                </h5>
                <div class="small text-muted">
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="card p-2">
            @if (session()->has('error'))
                <div class="alert alert-danger small text-center">
                    {{ session('error') }}
                </div>
                <br>
            @enderror
            @if (session()->has('info'))
                <div class="alert alert-info small text-center">
                    {{ session('info') }}
                </div>
                <br>
            @enderror
            @if (session()->has('success'))
                <div class="alert alert-success small text-center">
                    {{ session('success') }}
                </div>
                <br>
            @enderror

            @if ($propositions->where('etat', 'refusé')->count() > 0)
                <div class="d-flex justify-content-end">
                    <a href="#" class="link" wire:click = "retaurer()">
                        <b>Restaurer les autres réfusées</b>
                        ( {{ $propositions->where('etat', 'refusé')->count() }} )
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
                @forelse ($propositions as $item)
                    <tr>
                        <th scope="row" style="width: 51px">
                            <div class="avatar-small-product">
                                <img src="{{ Storage::url($item->acheteur->avatar) }}" alt="avtar">
                            </div>
                        </th>
                        <td>
                            <b>
                                <a href="/user/{{ $item->acheteur->id }}"
                                    class="link">{{ $item->acheteur->name }}
                                </a>
                                @if ($item->id_acheteur == $item->acheteur->id && $item->etat == 'accepté')
                                    <b class="text-success">
                                        VENDU <i class="bi bi-check2-square"></i>
                                    </b>
                                @endif
                            </b>
                            <br>
                            <span class="small">
                                <i>Membre dépuis {{ $item->acheteur->created_at }}</i>
                            </span>
                        </td>
                        <td>
                            {{ date('d/m/Y', strtotime($item->created_at)) }}
                        </td>
                        @if ($post->sell_at == null)
                            <td style="text-align: right">
                                <div class="btn-group" role="group"
                                    aria-label="Basic mixed styles example">
                                    @if ($item->etat != 'refusé')
                                        <button type="button" class="btn btn-danger"
                                            wire:click="supprimer({{ $item->acheteur->id }})">
                                            Réfuser l'offre
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-success"
                                        wire:click="accepter({{ $item->acheteur->id }})">
                                        Accepter l'offre
                                    </button>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                @endforelse
            </table>
</div>

</div>
</div>
