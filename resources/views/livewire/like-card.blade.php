<button type="button" wire:click="like()" class="badge badge-like-post-count position-absolute ab-right text-upper">
    @if ($liked)
        <i class="bi bi-suit-heart-fill color-red"></i>
    @else
        <i class="far fa-heart "></i>
    @endif

    <span>
        {{ $post->getLike->count() }}
    </span>
</button>
