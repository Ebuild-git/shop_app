<div style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
    @include('components.alert-livewire')
    @if (!$loading)
        @if (!$changed)
            @if ($post)
                @if (!$can_change)
                    <div class="text-center mb-4">
                        <h1 class="m-0 ft-regular text-danger h6">
                            <i class="bi bi-exclamation-octagon"></i>
                            {{ __('reduce_price') }}
                        </h1>
                    </div>
                    <form wire:submit='form_update_prix'>
                        <div class="mb-2">
                            <b>{{ __('current_price') }} :</b>
                            {{ $old_price }} <sup>{{ __('currency') }}</sup>
                            (
                            + {!! __('shopin_percentage') !!}
                            )
                        </div>

                        <label for="" class="strong color">
                            {{ __('ad') }}
                        </label>
                        {{ $titre }} <br>
                        <label for="" class="strong color">
                            {{ __('new_reduced_price') }}
                        </label>
                        <input type="number"
                            class="form-control border-r @error('prix') is-invalid @endif" placeholder="{{ __('less_than_price') }} {{ $old_price }} {{ __('currency') }}"
                step="0.1" wire:model='prix'>
            @error('prix')
                <div class="small text-center text-danger alert p-2">
                    <img width="30" height="30" src="/icons/error--v1.png"
            alt="error--v1" /> <br>
                    {!! $message !!}
                </div>
            @enderror
            @if ($show) <div class="text-end
                mt-3">
            <button type="submit" class="btn btn-sm bg-red">
                <span wire:loading>
                    <x-Loading></x-Loading>
                </span>

                {{ __('save_changes') }}
            </button> @endif
</form>
@else
        <div class="text-center p-2">
            <img width="64" height="64" src="https://img.icons8.com/wired/64/008080/calendar--v1.png"
                alt="calendar--v1" />
            <br>
            <p>
                {{ __('modification_blocked_title') }} {{ __('modification_blocked_message') }}
            </p>
            <div class="flex items-center mt-3">
                <span class="text-teal-600 text-xl font-bold">
                    <i style="color: #008080;" class="fas fa-clock"></i> {{ __('time_remaining_to_edit') }} <b style="color: #008080;">{{ $post->next_time_to_edit_price() }}</b>
                </span>
            </div>
        </div>



@endif
@else
<div class="text-center strong text-warning p-3">
    <img width="100" height="100" src="https://img.icons8.com/ios/100/FAB005/error--v1.png" alt="error--v1" />
    <br>
    Annonce introuvable / Signaler !
</div>
@endif
@endif
@else
<div class="text-center p-3">
    <x-Loading></x-Loading>
</div>
@endif
<br>

</div>
