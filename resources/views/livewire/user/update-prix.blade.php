<div>
    <b>Prix actuel :</b>
    {{ $old_price }} DH
    <br><br>
    <form wire:submit='form_update_prix'>
        <label for="" class="strong color">
            Nouveau prix réduis :
        </label>
        <input type="number" class="form-control border-r" placeholder="Max {{ $old_price }} DH" required step="0.1"
            wire:model='prix'>
        @error('prix')
            <span class="small text-danger">
                {{ $message }}
            </span>
        @enderror
        <br>

        @include('components.alert-livewire')
        @if (!$show)
            <br>
            <div class="text-center">
                <a href="/mes-publication" class="link color">
                    Voir les changements
                </a>
            </div>
        @endif
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
</div>
