<span>
    @auth
        <button type="button" class="btn-like-details " wire:click=like()>
            <div class="d-flex justify-content-between">
                <span class="my-auto">
                    {{ $total }}
                </span>
                <span class="my-auto">
                    @if ($liked === true)
                    <i class="bi bi-envelope-fill"></i>
                    @else
                    <i class="lni lni-heart mr-2 "></i>
                    @endif
                </span>
            </div>
        </button>
    @endauth

</span>
