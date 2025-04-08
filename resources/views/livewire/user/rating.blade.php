<div>
    @if ($last_buy)
    <div class="alert alert-info">
        <i class="bi bi-bag-check"></i>
        {{ __('recent_purchase', ['username' => $user->username, 'date' => $last_buy->sell_at]) }}
        <div>
            <b>
                <i class="bi bi-arrow-right"></i> {{ \App\Traits\TranslateTrait::TranslateText(Str::limit($last_buy->titre, 40)) }}
            </b> <br>
            @if ($rate)
            <i class="bi bi-arrow-right"></i> {{ __('rating', ['stars' => $rate->etoiles]) }}
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
                wire:click="rate_action({{ $i + 1 }})">
                <i class="bi bi-star-fill"></i> {{ $i + 1 }}
                </button>
                @endfor

        </div>
    </div>
    @else
    <div class="p-3 alert-danger">
        <b class="h5 text-danger">
            {{ __('attention') }}
        </b>
        @if (!$rate)
        <p>
            {{ __('leave_review_info') }}
        </p>
        @else
        <p>
            {{ __('no_more_review') }}
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
