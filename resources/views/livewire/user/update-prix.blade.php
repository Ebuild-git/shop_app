<div>
    @include('components.alert-livewire')
    @if (!$loading)
        @if (!$changed)
            @if ($post)
                @if (!$can_change)
                    <div class="text-center mb-4">
                        <h1 class="m-0 ft-regular text-danger h6">
                            <i class="bi bi-exclamation-octagon"></i>
                            Réduire le prix
                        </h1>
                    </div>
                    <form wire:submit='form_update_prix'>
                        <div class="mb-2">
                            <b>Prix actuel :</b>
                            {{ $old_price }} <sup>DH</sup>
                            (
                            + Pourcentage de <b class="color">Shopin</b>
                            )
                        </div>

                        <label for="" class="strong color">
                            Annonce :
                        </label>
                        {{ $titre }} <br>
                        <label for="" class="strong color">
                            Nouveau prix réduis :
                        </label>
                        <input type="number"
                            class="form-control border-r @error('prix') is-invalid @endif" placeholder="Max {{ $old_price }} DH"
                required step="0.1" wire:model='prix'>
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
                Enregistrer le changement
            </button> @endif
</form>
@else
        <div class="text-center p-2">
            <img width="64" height="64" src="https://img.icons8.com/wired/64/008080/calendar--v1.png"
                alt="calendar--v1" />
            <br>
            <p>
                Vous ne pouvez pas modifier cette annonce car elle a été modifiée il y a plus moins d'une
                semaine.
            </p>
            <div class="flex items-center mt-3">
                <span class="text-teal-600 text-xl font-bold">
                    <i style="color: #008080;" class="fas fa-clock"></i> Temps restant avant de pouvoir modifier : <b style="color: #008080;">{{ $post->next_time_to_edit_price() }}</b>
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
