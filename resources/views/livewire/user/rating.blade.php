<div>
    @if ($last_buy)
    <div class="alert alert-info">
        <i class="bi bi-bag-check"></i>
        Votre achat le plus récent chez <b>{{ $user->username }}</b> date du : {{ $last_buy->sell_at }}
        <div>
            <b>
                <i class="bi bi-arrow-right"></i> {{ Str::limit($last_buy->titre, 40) }}
            </b> <br>
            @if ($rate)
            <i class="bi bi-arrow-right"></i> Note : {{ $rate->etoiles }} / 5
            @for ($i = 0; $i < $rate->etoiles; $i++)
                <i class="bi bi-star-fill"></i>
                @endfor
                @endif
        </div>
    </div>
    @endif
    @if ($can_rate)
    <div>
        <div class="d-flex justify-content-between">
            @for ($i = 0; $i < 5; $i++) <button type="button" class="w-100 btn-note-rating"
                wire:click='rate({{ $i + 1 }})'>
                <i class="bi bi-star-fill"></i> {{ $i + 1 }}
                </button>
                @endfor

        </div>
    </div>
    @else
    <div class="p-3 alert-danger">
        <b class="h5 text-danger">
            Attention !
        </b>
        @if (!$rate)
        <p>
            Vous pouvez laisser un avis sous forme d'étoiles uniquement si vous avez effectué un achat auprès de ce
            vendeur !
        </p>
        @else
        <p>
            Vous pouvez ne pouvez plus laisser un avis car vous l'avez déja fait !
        </p>
        @endif
    </div>
    @endif
    @include('components.alert-livewire')
    <div class="text-center">
        <span wire:loading>
            <x-Loading></x-Loading>
        </span>
    </div>
</div>