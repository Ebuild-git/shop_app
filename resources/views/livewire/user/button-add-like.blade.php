<span>
    @auth
        <button class="btn custom-height btn-default btn-block mb-2 @if ($liked === true) btn-liked @endif "
            type="button" wire:click=like()>
        @endauth
        @guest
            <button class="btn custom-height btn-default btn-block mb-2 " type="button" data-toggle="modal"
                data-target="#login">
            @endguest
            <span wire:loading>
                <i class="lni lni-heart mr-2"></i>
            </span>

            <span wire:loading.remove>
                <i class="lni lni-heart mr-2"></i>
                J'aime ( {{ $total }} )
            </span>
        </button>

</span>
