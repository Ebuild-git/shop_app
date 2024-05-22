<div>
    <b>Prix actuel :</b>
    {{ $old_price }} DH
    <br><br>
    <form wire:submit='form_update_prix'>
        <label for="">
            Nouveau prix
        </label>
        <input type="number" class="form-control" placeholder="{{ $old_price }} DH" required step="0.1"
            wire:model='prix'>
        @error('prix')
            <span class="small text-danger">
                {{ $message }}
            </span>
        @enderror
        <br>

        @include('components.alert-livewire')

        <div class="modal-footer">
            <button type="submit" class="btn btn-sm bg-red">
                <span wire:loading>
                    <x-Loading></x-Loading>
                </span>
                Enregistrer le changement
            </button>
        </div>
    </form>
</div>
