<div>

    @if (!$changed)
        @if ($post)
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
                + {{ $post->getPrix() - $old_price }} DH Frais de <b class="color">Shopin</b>
                )
                <br><br>
                <label for="" class="strong color">
                    Annonce :
                </label>
                {{ $titre }} <br>
                <label for="" class="strong color">
                    Nouveau prix réduis :
                </label>
                <input type="number" class="form-control border-r" placeholder="Max {{ $old_price }} DH" required
                    step="0.1" wire:model='prix'>
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
            <div class="text-center strong text-warning p-3">
                <img width="100" height="100" src="https://img.icons8.com/ios/100/FAB005/error--v1.png"
                    alt="error--v1" />
                <br>
                Impossible de Réduire le prix !
            </div>
        @endif
    @endif

    @include('components.alert-livewire')
    @if ($changed)
        <br>
        <div class="text-center">
            <a href="/mes-publication" class="link color">
                Appliquer et Voir les changements
            </a>
        </div>
    @endif
</div>
