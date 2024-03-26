<div>
    <div>
        Note moyenne : 
        {{ number_format($notes, 1) }}
        <i class="bi bi-star-fill" style="color: #fab005;"></i>
    </div>
    <br>
    @auth
    <div class="d-flex justify-content-center">
        <div>
            @for ($i = 1; $i <= 5; $i++)
            <button type="button" wire:click="rate('{{$i}}')" class="btn-rating-modal {{ $ma_note   >= $i ? 'rating-yellow-color' : 'none' }} ">
                <i class="bi bi-star-fill"></i>
            </button>
            @endfor
        </div>
    </div>
    @endauth
    @guest
        <div class="alert alert-danger">
            Pour ajouter une note, vous devez être connecté.
        </div>
    @endguest
    
</div>

