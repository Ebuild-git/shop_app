@if ($post->statut == 'vente')
    @auth
        @if ($post->id_user != Auth::id())
            <button type="button" class="btn btn-block bg-dark mb-2 p-3 " data-toggle="modal" data-target="#login"
                onclick="add_cart({{ $post->id }})">
                <i class="lni lni-shopping-basket mr-2"></i>
                Ajouter au panier
            </button>
        @endif
    @endauth

    @guest
        <button type="button" class="btn btn-block bg-dark mb-2 p-3 " data-toggle="modal" data-target="#login">
            <i class="lni lni-shopping-basket mr-2"></i>
            Ajouter au panier
        </button>
    @endguest
@endif
