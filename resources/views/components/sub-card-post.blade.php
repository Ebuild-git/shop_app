<div class="card-footer b-0 p-0 pt-2 bg-white">
    @if ($show)
    <div class="d-flex justify-content-between mb-1" style="font-size: 12px;">
        <div class="d-flex justify-content-between mb-1">
            @if ($post->changements_prix->count())
            <strike class="elis_rty strong" style="font-size: 12px; color: 'a19e9e'; ">
                {{ $post->getOldPrix() }} <sup>{{ __('currency') }}</sup>
            </strike>
            &nbsp;&nbsp;&nbsp;
            @endif
            <div class="@if ($post->changements_prix->count()) @endif ft-bold"  style="font-size: 12px; color: #008080;">
                {{ $post->getPrix() }} <sup>{{ __('currency') }}</sup>
            </div>
        </div>
        @if ($post->sous_categorie_info->categorie->luxury == 1)
        <div class="color strong" style="font-size: 12px;">
            <i class="bi bi-gem"></i>
            <span class="luxury-text">{{ __('luxury') }}</span>
        </div>
        @endif
    </div>
    <div>
        <div class="text-left">
            <h4 class="fw-bolder fs-md mb-0 lh-1 mb-1">
                <a href="/post/{{ $post->id }}/{{ Str::slug($post->titre) }}" class="text-capitalize">
                {{ \App\Traits\TranslateTrait::TranslateText(strtolower(Str::limit($post->titre, 25))) }}
                </a>
            </h4>
            <div class="d-flex justify-content-between">
                <div class="categorie-title1">

                    {{ \App\Traits\TranslateTrait::TranslateText($post->sous_categorie_info->titre) }}
                </div>
                @if ($post->proprietes)
                    <div class="strong1">
                        @foreach ($post->proprietes as $key => $value)
                            @if (\Illuminate\Support\Str::startsWith(strtolower($key), 'taille'))
                                {{ $value }}
                                @break
                            @elseif (\Illuminate\Support\Str::startsWith(strtolower($key), 'pointure'))
                                {{ $value }}
                                @break
                            @endif
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
    @endif
</div>

<style>
    .color.strong i {
    margin-right: 1px;
    }

    .color.strong .luxury-text {
        margin-left: 0;
    }

    @media (max-width: 768px) {
    .luxury-text {
        display: none;
    }
    .color.strong i {
        font-weight: bold;
    }
}
</style>
