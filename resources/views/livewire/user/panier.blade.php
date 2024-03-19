<div>
    <div>

        <!-- Single Item -->
        @php
            $prix_total = 0;
        @endphp
        @forelse ($cart as $item)
            @php
                $post = DB::table('posts')
                    ->where('id', $item['id'])
                    ->select('photos', 'titre', 'prix')
                    ->first();
            @endphp
            @if ($post)
                @php
                    $prix_total += $post->prix;
                @endphp
                <div class="d-flex align-items-center justify-content-between br-bottom px-3 py-3">
                    <div class="cart_single d-flex align-items-center">
                        <div class="cart_selected_single_thumb">
                            <a href="#">
                                <img src="{{ Storage::url($post->photos[0] ?? '') }}" width="60" class="img-fluid"
                                    alt="" />
                            </a>
                        </div>
                        <div class="cart_single_caption pl-2">
                            <h4 class="product_title fs-sm ft-medium mb-0 lh-1">
                                {{ $post->titre }}
                            </h4>
                            <h4 class="fs-md ft-medium mb-0 lh-1 color">
                                {{ $post->prix }} DH
                            </h4>
                        </div>
                    </div>
                    <div class="fls_last">
                        <button class="close_slide gray" type="button" wire:click="delete({{ $item['id'] }})">
                            <i class="ti-close"></i>
                        </button>
                    </div>
                </div>
            @endif
        @empty
            <div class="d-flex align-items-center justify-content-between br-bottom px-3 py-3">
                Aucun article dans le panier.
            </div>
        @endforelse

    </div>

    <div class="d-flex align-items-center justify-content-between br-top br-bottom px-3 py-3">
        <h6 class="mb-0">
            Prix total
        </h6>
        <h3 class="mb-0 ft-medium color">
            {{ $prix_total }} DH
        </h3>
    </div>

</div>
