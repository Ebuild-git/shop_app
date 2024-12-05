<div>
    <div class="container">
        <h3>Suivi de l'expédition</h3>

        <div class="mb-3">
            <label for="shipmentId" class="form-label">ID d'expédition</label>
            <input type="text" id="shipmentId" wire:model="shipmentId" class="form-control" placeholder="Entrez l'ID de l'expédition">
        </div>

        <button wire:click="trackShipment" class="btn btn-primary">Suivre l'expédition</button>

        @if ($shipmentStatus)
            <div class="mt-4">
                <h4>Statut de l'expédition:</h4>
                <p class="text-success">{{ $shipmentStatus }}</p>
            </div>
        @endif

        @if ($errorMessage)
            <div class="mt-4">
                <p class="text-danger">{{ $errorMessage }}</p>
            </div>
        @endif
    </div>
</div>
