<div>
    <div class="container py-5">
        <div class="mx-auto" style="max-width: 600px;">
            <div class="bg-white border border-secondary-subtle rounded-3 shadow-sm p-4">

                <h3 class="text-center mb-4 fw-bold text-black">Suivi de l'expédition</h3>

                <div class="mb-4">
                    <label for="shipmentId" class="form-label fw-semibold">ID d'expédition</label>
                    <input
                        type="text"
                        id="shipmentId"
                        wire:model="shipmentId"
                        class="form-control border-r shadow-none form-control-lg"
                        placeholder="Entrez l'ID de l'expédition"
                    >
                </div>
                <button
                    wire:click="trackShipment"
                    wire:loading.attr="disabled"
                    class="bg d-flex align-items-center justify-content-center gap-2"
                    type="button"
                >
                    <span>
                        Suivre l'expédition
                    </span>
                    <span wire:loading wire:target="trackShipment">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </span>
                </button>

                @if ($error)
                    <div class="alert alert-danger py-2 px-3 mb-3 rounded-2 shadow-sm" role="alert">
                        {{ $error }}
                    </div>
                @endif
                @if ($trackingResponse && isset($trackingResponse['TrackingResults']))
                    @php
                        $results = $trackingResponse['TrackingResults'];
                    @endphp

                    @if (empty($results))
                        <div class="alert alert-info mt-3">
                            Aucune information de suivi disponible pour le moment.
                        </div>
                    @else
                        <div class="bg-light border border-secondary rounded-3 p-3 mt-3" style="max-height: 350px; overflow-y: auto;">
                            <h5 class="fw-semibold mb-3">Détails du suivi :</h5>

                            @foreach ($results as $tracking)
                                @foreach ($tracking['Value'] as $entry)
                                    <div class="mb-3 p-3 border rounded bg-white shadow-sm">
                                        <p class="mb-1"><strong>Numéro de suivi :</strong> {{ $entry['WaybillNumber'] }}</p>
                                        <p class="mb-1"><strong>Statut :</strong> {{ $entry['UpdateDescription'] }}</p>
                                        <p class="mb-1"><strong>Date :</strong>
                                            {{ \Carbon\Carbon::parse(substr($entry['UpdateDateTime'], 6, 10))->format('d/m/Y H:i') }}
                                        </p>
                                        <p class="mb-1"><strong>Lieu :</strong> {{ $entry['UpdateLocation'] }}</p>
                                        @if (!empty($entry['Comments']))
                                            <p class="mb-1"><strong>Commentaires :</strong> {{ $entry['Comments'] }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    @endif
                @endif


            </div>
        </div>
    </div>
</div>

