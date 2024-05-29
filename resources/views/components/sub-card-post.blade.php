<div class="card-footer b-0 p-0 pt-2 bg-white">
    @if ($show)
        <div class="d-flex justify-content-between mb-1">
            <div class="d-flex justify-content-between mb-1">
                @if ($post->old_prix)
                    <strike class="elis_rty color strong">
                        {{ $post->getOldPrix() }} DH
                    </strike>
                    &nbsp;&nbsp;&nbsp;
                @endif
                <div class="@if ($post->old_prix) text-danger @else color @endif ft-bold fs-sm">
                    {{ $post->getPrix() }} DH
                </div>
            </div>
            @if ($post->sous_categorie_info->categorie->luxury == 1)
                <div class="color strong">
                    <i class="bi bi-gem"></i>
                    LUXURY
                </div>
            @endif
        </div>
        <div>
            <div class="text-left">
                <h4 class="fw-bolder fs-md mb-0 lh-1 mb-1">
                    <a href="/post/{{ $post->id }}">
                        {{ Str::limit($post->titre, 40) }}
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
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
