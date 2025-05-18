<div class="container py-5">
    <div class="mx-auto" style="max-width: 600px;">
        <div class="bg-white border rounded-4 shadow p-4">

            <h2 class="text-center fw-bold text-dark mb-4">
                📦 {{ __('shipment_tracking.title')}}
            </h2>

            <div class="mb-3">
                <label for="shipmentId" class="form-label fw-semibold">{{ __('shipment_tracking.label')}}</label>
                <input
                    type="text"
                    id="shipmentId"
                    wire:model="shipmentId"
                    class="form-control form-control-lg"
                    placeholder="{{ __('shipment_tracking.placeholder')}}"
                    aria-label="ID d'expédition"
                >
            </div>

            <div class="d-grid">
                <button
                    wire:click="trackShipment"
                    wire:loading.attr="disabled"
                    class="bg d-flex align-items-center justify-content-center gap-2"
                    type="button"
                >
                    <span>
                        🔍 {{ __('shipment_tracking.track_button')}}
                    </span>
                    <span wire:loading wire:target="trackShipment">
                        <span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>
                    </span>
                </button>
            </div>
            @if ($error)
                <div class="alert alert-danger mt-4 rounded-3" role="alert">
                    {{ $error }}
                </div>
            @endif

            @if ($trackingResponse && isset($trackingResponse['TrackingResults']))
                @php $results = $trackingResponse['TrackingResults']; @endphp

                @if (empty($results))
                    <div class="alert alert-info mt-4">
                        {{ __('shipment_tracking.no_info')}}
                    </div>
                @else
                    <div class="mt-4 bg-light p-3 rounded-3 border" style="max-height: 400px; overflow-y: auto;">
                        <h5 class="fw-semibold mb-3">🗂️ {{ __('shipment_tracking.details')}}</h5>

                        @foreach ($results as $tracking)
                            @foreach ($tracking['Value'] as $entry)
                                @php
                                    preg_match('/\/Date\((\d+)(?:[+-]\d+)?\)\//', $entry['UpdateDateTime'], $matches);
                                    $timestamp = isset($matches[1]) ? intval($matches[1]) / 1000 : null;
                                    $date = $timestamp ? \Carbon\Carbon::createFromTimestamp($timestamp)->format('d/m/Y ') : '';
                                @endphp
                                <div class="mb-3 p-3 bg-white border-start border-4 border-primary rounded shadow-sm">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="mb-1 text-muted small">{{ __('shipment_tracking.number')}} : <strong>{{ $entry['WaybillNumber'] }}</strong></p>
                                            <h6 class="fw-bold mb-1">
                                                <span class="badge bg-primary">
                                                    {{ \App\Traits\TranslateTrait::TranslateText($entry['UpdateDescription']) }}
                                                </span>
                                            </h6>
                                            @if ($date)
                                                <p class="mb-0 text-muted"><i class="bi bi-calendar-event"></i> {{ $date }}</p>
                                            @endif
                                            @if (!empty($entry['UpdateLocation']))
                                                <p class="mb-0 text-muted"><i class="bi bi-geo-alt-fill"></i> {{ $entry['UpdateLocation'] }}</p>
                                            @endif
                                            @if (!empty($entry['Comments']))
                                                <p class="mb-0 text-muted"><i class="bi bi-chat-dots"></i> {{ \App\Traits\TranslateTrait::TranslateText($entry['Comments']) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
