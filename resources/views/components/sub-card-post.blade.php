<div class="card-footer b-0 p-0 pt-2 bg-white">
    @if ($show)
        <div class="d-flex justify-content-between">
            @if ($post->old_prix)
                <div>
                    <strike>
                        <span class="elis_rty color">
                            {{ $post->getOldPrix() }} DH
                        </span>
                    </strike>
                </div>
            @endif
            <div class="@if ($post->old_prix) text-danger @else color @endif">
                <span class="ft-bold  fs-sm">
                    {{ $post->getPrix() }} DH
                </span>
            </div>
            @if ($post->sous_categorie_info->categorie->luxury == 1)
                <div>
                    <span class="color">
                        <b>
                            <i class="bi bi-gem"></i>
                            LUXURY
                        </b>
                    </span>
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
                <div>
                    {{ $post->sous_categorie_info->titre }}
                </div>
            </div>
        </div>
    @endif
</div>
