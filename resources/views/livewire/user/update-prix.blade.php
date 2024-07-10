<div>

    @if (!$changed)
        @if ($post)
            @if (!$can_change)
                <div class="text-center mb-4">
                    <h1 class="m-0 ft-regular text-danger h6">
                        <i class="bi bi-exclamation-octagon"></i>
                        Réduire le prix
                    </h1>
                    <span class="text-muted">
                    </span>
                </div>
                <form wire:submit='form_update_prix'>
                    <b>Prix actuel :</b>
                    {{ $old_price }} DH
                    (
                    + Pourcentage de <b class="color">Shopin</b>
                    )
                    <br><br>
                    <label for="" class="strong color">
                        Annonce :
                    </label>
                    {{ $titre }} <br>
                    <label for="" class="strong color">
                        Nouveau prix réduis :
                    </label>
                    <input type="number" class="form-control border-r" placeholder="Max {{ $old_price }} DH"
                        required step="0.1" wire:model='prix'>
                    @error('prix')
                        <span class="small text-danger">
                            {{ $message }}
                        </span>
                    @enderror
                    <br>
                    @if ($show)
                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-sm bg-red">
                                <span wire:loading>
                                    <x-Loading></x-Loading>
                                </span>
                                Enregistrer le changement
                            </button>
                        </div>
                    @endif
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
                </div>
            @endif
        @else
            <div class="text-center strong text-warning p-3">
                <img width="100" height="100" src="https://img.icons8.com/ios/100/FAB005/error--v1.png"
                    alt="error--v1" />
                <br>
                Annonce introuvable / Signaler !
            </div>
        @endif
    @endif

    @include('components.alert-livewire')
</div>
