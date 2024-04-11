<div>
    <b>Prix actuel :</b>
    {{ $post->prix }} DH
    <br><br>
    <form wire:submit='form_update_prix'>
        <label for="">
            Nouveau prix
        </label>
        <input type="number" class="form-control" step="0.1" wire:model='prix'>
        <br>
        
        @include('components.alert-livewire')

        <div class="modal-footer">
            <button type="submit" class="btn btn-sm bg-red">
                <span wire:loading>
                    <x-Loading></x-Loading>
                </span>
                Enregistrer le changement.
            </button>
        </div>
    </form>
</div>
