<div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    <div class="text-center mb-4">
        <h3 class="mb-2">Frais de Livraison</h3>
        <p class="text-muted">
            Veuillez entrer la valeur en % des frais appliquer sur chaque commande
        </p>
        @if (session()->has('error'))
            <span class="text-danger small">
                {{ session('error') }}
            </span>
        @enderror
        @if (session()->has('success'))
            <span class="text-success small">
                {{ session('success') }}
            </span>
        @enderror
</div>
<form wire:submit="enregistrer">
<div class="col-12">
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">valeur</span>
            <input ype="number" step="0.1" class="form-control" wire:model="valeur">
          </div>
    <br>
</div>
<div class="col-12 text-center">
    <button type="submit" class="btn btn-primary me-sm-3 me-1">
        <x-loading></x-loading>
        Enregistrer
    </button>
    <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
        Cancel
    </button>
</div>
</form>

<!--/ Add New Credit Card Modal -->

</div>
