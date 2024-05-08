<span>
    @auth
        <button type="button" class="btn-like-details" wire:click=like()>
            {{ $total }}
            @if ($liked === true)
                <img src="/icons/icons8-aimer-50.png" height="16" width="16" alt="" srcset="">
            @else
                <i class="lni lni-heart mr-2 "></i>
            @endif
        </button>
    @endauth

</span>
