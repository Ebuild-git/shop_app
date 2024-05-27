<div>
    <b>Prix actuel :</b>
    {{ $old_price }} DH
    <br><br>
    <form wire:submit='form_update_prix'>
        <div class="alert alert-info">
            Vous n'avez pas la possibilité de mettre le nouveau prix supérieur à l'ancien.
        </div>
        <label for="">
            Nouveau prix
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
