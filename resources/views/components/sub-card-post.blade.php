<div class="card-footer b-0 p-0 pt-2 bg-white">
    @if ($show)
        <div class="d-flex justify-content-between mb-2">
            @if ($post->old_prix)
                <strike class="elis_rty color">
                    {{ $post->getOldPrix() }} DH
                </strike>
            @endif
            <div class="@if ($post->old_prix) text-danger @else color @endif ft-bold fs-sm">
                {{ $post->getPrix() }} DH
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
                        @if ($post->proprietes['Taille'] ?? null)
                            <div>
                                {{ $post->proprietes['Taille'] }}
                            </div>
                        @endif
                    @endif
                </div>
                
            </div>
        </div>
    @endif
</div>
