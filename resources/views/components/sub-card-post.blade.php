<div class="card-footer b-0 p-0 pt-2 bg-white">
    @if ($show)
    <div class="d-flex justify-content-between mb-1" style="font-size: 12px;">
        <div class="d-flex justify-content-between mb-1">
            @if ($post->changements_prix->count())
            <strike class="elis_rty color strong" style="font-size: 12px;">
                {{ $post->getOldPrix() }} <sup>DH</sup>
            </strike>
            &nbsp;&nbsp;&nbsp;
            @endif
            <div class="@if ($post->changements_prix->count()) text-danger @else color @endif ft-bold"  style="font-size: 12px;">
                {{ $post->getPrix() }} <sup>DH</sup>
            </div>
        </div>
        @if ($post->sous_categorie_info->categorie->luxury == 1)
        <div class="color strong" style="font-size: 12px;">
            <i class="bi bi-gem"></i>
            LUXURY
        </div>
        @endif
    </div>
    <div>
        <div class="text-left">
            <h4 class="fw-bolder fs-md mb-0 lh-1 mb-1">
                <a href="/post/{{ $post->id }}/{{ Str::slug($post->titre) }}" class="text-capitalize">
                    {{ strtolower(Str::limit($post->titre, 25)) }}
                </a>
            </h4>
            <div class="d-flex justify-content-between">
                <div>
                    {{ $post->sous_categorie_info->titre }}
                </div>
                @if ($post->proprietes)
                <div class="strong">
                    @if ($post->proprietes['Taille'] ?? null)
                    {{ $post->proprietes['Taille'] }}
                    @elseif($post->proprietes['Pointure'] ?? null)
                    {{ $post->proprietes['Pointure'] }}
                    @elseif($post->proprietes['Pointure - Bébé'] ?? null)
                    {{ $post->proprietes['Pointure - Bébé'] }}
                    @elseif($post->proprietes['Pointure- Bébé'] ?? null)
                    {{ $post->proprietes['Pointure- Bébé'] }}
                    @elseif($post->proprietes['Taille - bébé'] ?? null)
                    {{ $post->proprietes['Taille - bébé'] }}
                    @else
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
