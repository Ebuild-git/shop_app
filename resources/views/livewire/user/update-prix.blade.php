<div>
    <b>Prix actuel :</b>
    {{ $old_price }} DH
    <br><br>
    <form wire:submit='form_update_prix'>
        <label for="">
            Nouveau prix réduis :
        </label>
        <input type="number" class="form-control" placeholder="Max {{ $old_price }} DH" required step="0.1"
            wire:model='prix'>
        @error('prix')
            <span class="small text-danger">
                {{ $message }}
            </span>
        @enderror
        <br>

        @include('components.alert-livewire')
        @if ($show)
            <div class="modal-footer">
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
