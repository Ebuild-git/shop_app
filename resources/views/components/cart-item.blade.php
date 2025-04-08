<div>
    <div class="d-flex align-items-center justify-content-between br-bottom px-3 py-3">
        <div class="cart_single d-flex align-items-center">
            <div class="cart_selected_single_thumb">
                <img src="{{ $produit->FirstImage() }}" class="img-fluid" alt="{{ $produit->titre }}" />
            </div>
            <div class="cart_single_caption pl-2">
                <a href="/post/{{$produit->id}}">
                    <h4 class="product_title fs-sm ft-medium mb-0 lh-1 text-capitalize">
                        {{ Str::limit($produit->titre, 15) }}
                    </h4>
                </a>
                <div class="text-muted ">
                    {{ __('seller')}} : {{ $produit->user_info->username }}
                </div>
            </div>
        </div>
        <div class="ft-bold fs-sm text-end">
            @if ($produit->changements_prix->count())
                <strike class="text-muted">
                    {{ $produit->getOldPrix() }} <sup>{{ __('currency') }}</sup>
                </strike>

            @endif
            <div class="@if ($produit->changements_prix->count()) color @else text-muted @endif ft-bold fs-sm">
                {{ $produit->getPrix() }} <sup>{{ __('currency') }}</sup>
            </div>
            <br>
        </div>
        <div class="cart_single_close">
            <button class="close_slide gray" type="button" onclick="remove_to_card({{ $produit->id }})">
                <i class="ti-trash text-danger"></i>
            </button>
        </div>
    </div>
    <style>
        .product_title:hover{
            text-decoration: underline;
            color: #008080;
        }
    </style>
</div>
