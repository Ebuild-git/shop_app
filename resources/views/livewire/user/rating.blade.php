<div>
    <div class="d-flex justify-content-between">
        <div>
            {{ $avis }} Avis
        </div>
        <div>
            @for ($i = 1; $i <= 5; $i++)
            <button type="button" wire:click="rate('{{$i}}')" class="btn-rating-modal {{ $ma_note   >= $i ? '' : 'none' }} ">
                <i class="bi bi-star-fill"></i>
            </button>
            @endfor
        </div>
    </div>
</div>



