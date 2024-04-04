<span>
    @auth
        <button class="btn custom-height btn-default btn-block mb-2 @if ($liked === true) btn-favoris-added @endif "
            type="button" wire:click=add_favoris()>
        @endauth
        @if ($liked === true)
            <i class="bi bi-check-circle"></i> 
            &nbsp;
        @endif
        <span wire:loading>
            <i class="lni lni-heart mr-2"></i>
        </span>
        Favoris
    </button>

</span>
