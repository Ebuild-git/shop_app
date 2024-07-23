<div>
    @if ($can_rate)
    <div>
        @if ($last_buy)
        <div class="alert alert-info">
            <i class="bi bi-bag-check"></i>
            Votre achat le plus récent chez <b>{{ $user->username }}</b> date du : {{ $last_buy->sell_at}}
            <div>
                <b>
                    <i class="bi bi-arrow-right"></i> {{ Str::limit( $last_buy->titre , 40)}}
                </b>
            </div>
        </div>
        @endif
        <div class="d-flex justify-content-between">
            @for ($i = 0; $i < 5; $i++) <button type="button" class="w-100 btn-note-rating"
                wire:click='rate({{ $i+1 }})'>
                <i class="bi bi-star-fill"></i> {{ $i+1 }}
                </button>
                @endfor

        </div>
    </div>
    @else
    <div class="text-center p-3 alert-danger">
        <b class="h5 text-danger">
            Attention !
        </b>
        <p>
            Vous pouvez laisser un avis sous forme d'étoiles uniquement si vous avez effectué un achat auprès de ce
            vendeur !
        </p>
    </div>
    @endif
    @include('components.alert-livewire')

</div>