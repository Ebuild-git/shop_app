<table class="table">
    <thead style="background-color: #008080;color: white !important;">
        <tr>
            <th scope="col" style="width: 51px;"></th>
            <th scope="col">Nom de l'article</th>
            <th scope="col">Date d'achat</th>
            <th scope="col">Prix d'achat</th>
            <th scope="col">Shopiner</th>
            <th scope="col" class="text-end">Satut de l'xpedition</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($achats as $achat)
            <tr>
                <td style="width: 41px;">
                    <div class="avatar-small-product">
                        <img src="{{ Storage::url($achat->photos[0] ?? '') }}" alt="avtar">
                    </div>
                </td>
                <td>
                    <a href="/post/{{ $achat->id }}" class="link h6"> {{ $achat->titre }} </a>
                </td>
                <td>
                    {{  \Carbon\Carbon::parse($achat->sell_at)->format("d-m-Y") }}
                </td>
                <td>
                    <span class="strong color">
                        <i class="bi bi-tag"></i>
                        {{ $achat->getPrix() }}
                        DH
                    </span>
                </td>
                <td>
                    @if ($achat->user_info)
                        <a href="{{ route('user_profile', ['id' => $achat->user_info->id]) }}">
                            {{ $achat->user_info->username }}
                        </a>
                    @endif
                </td>
                <td class="text-end">
                    <x-StatutLivraison :statut="$achat->statut"></x-StatutLivraison>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">
                    <div class="alert alert-info text-center">
                        <div>
                            <img width="100" height="100"
                                src="https://img.icons8.com/carbon-copy/100/737373/shopping-cart-loaded.png"
                                alt="shopping-cart-loaded" />
                        </div>
                        <h6 class="text-center">Aucun Achat !</h6>
                        <span class="text-muted">
                            <i> 
                                vous n'avez pas d'achat actuellement .
                            </i>
                        </span>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>