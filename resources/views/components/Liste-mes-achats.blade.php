<div id="table-wrapper">
    <div id="table-scroll">
        <table class="table">
            <thead>
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
                                <sup>DH</sup>
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
    </div>
</div>
<div class="sticky-pagination-container">
    <div class="modern-pagination">
        <span class="page-indicator">Page {{ $achats->currentPage() }} of {{ $achats->lastPage() }}</span>
        <button class="page-button" {{ $achats->onFirstPage() ? 'disabled' : '' }} onclick="location.href='{{ $achats->previousPageUrl() }}'">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="page-button" {{ !$achats->hasMorePages() ? 'disabled' : '' }} onclick="location.href='{{ $achats->nextPageUrl() }}'">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</div>
<style>
    .sticky-pagination-container {
    position: sticky;
    bottom: 0;
    background-color: #ffffff;
    padding: 10px 15px;
    border-top: 1px solid #ddd;
    box-shadow: 0 2px 4px rgba(255, 255, 255, 0);
    z-index: 10;
    display: flex;
    justify-content: flex-start;
    align-items: center;
}

/* Responsive Design Adjustment */
@media (max-width: 768px) {
    .sticky-pagination-container-left {
        padding: 8px 10px;
    }
}

</style>
