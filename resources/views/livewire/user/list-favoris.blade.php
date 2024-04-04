<div>
    <table class="table">
        <thead style="background-color: #008080;color: white !important;">
            <tr>
                <td></td>
                <td>
                    Publication
                </td>
                <td>
                    Prix
                </td>
                <td>
                    Date
                </td>
                <td>

                </td>
            </tr>
        </thead>
        <tbody>
            @forelse ($favoris as $favoris)
                <tr>
                    <th scope="row">
                        <div class="avatar-small-product">
                            <img src="{{ Storage::url($favoris->post->photos[0] ?? '') }}" alt="avtar">
                        </div>
                    </th>
                    <td>
                       <b>
                        <a href="/post/{{ $favoris->post->id }}">
                            {{ $favoris->post->titre }}
                        </a>
                       </b>
                    </td>
                    <td>
                        {{ $favoris->post->getPrix() }} DH
                    </td>
                    <td>
                        {{ $favoris->post->created_at }}
                    </td>
                    <td style="text-align: right;">
                        <button class="btn btn-danger btn-sm" wire:click="delete({{$favoris->post->id}})">
                            <i class="bi bi-trash3"></i> Retirer
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <div class="alert alert-warning">
                            Aucun favori trouvé!
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
