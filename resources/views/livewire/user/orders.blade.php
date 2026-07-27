<div class="container py-5">
    <div class="mx-auto" style="max-width: 640px;">
        <div class="tracking-card">

            <div class="tracking-header text-center">
                <div class="tracking-icon">📦</div>
                <h2 class="fw-bold mb-1">{{ __('shipment_tracking.title') }}</h2>
                <p class="text-muted mb-0 small">{{ __('shipment_tracking.label') }}</p>
            </div>

            <div class="tracking-body">
                <div class="input-group input-group-lg mb-3 shipment-input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-upc-scan text-teal"></i>
                    </span>
                    {{-- <input
                        type="text"
                        id="shipmentId"
                        wire:model="shipmentId"
                        class="form-control border-start-0 ps-0"
                        placeholder="{{ __('shipment_tracking.placeholder') }}"
                        aria-label="ID d'expédition"
                    > --}}
                    <input
                        type="text"
                        id="shipmentId"
                        wire:model="shipmentId"
                        class="form-control border-start-0 ps-0"
                        placeholder="{{ __('shipment_tracking.placeholder') }}"
                        aria-label="ID d'expédition"
                    >
                </div>

                <div class="d-grid">
                    <button
                        wire:click="trackShipment"
                        wire:loading.attr="disabled"
                        wire:target="trackShipment"
                        class="btn-track d-flex align-items-center justify-content-center gap-2"
                        type="button"
                    >
                        <span wire:loading.remove wire:target="trackShipment">
                            <i class="bi bi-search"></i>
                        </span>
                        <span wire:loading wire:target="trackShipment" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span>{{ __('shipment_tracking.track_button') }}</span>
                    </button>
                </div>

                @if ($error)
                    <div class="alert-modern alert-modern-danger mt-3">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <span>{{ $error }}</span>
                    </div>
                @endif
                @if ($trackingResponse && isset($trackingResponse['TrackingResults']))
                    @php $results = $trackingResponse['TrackingResults']; @endphp

                    @if (empty($results))
                        <div class="alert-modern alert-modern-info mt-4">
                            <i class="bi bi-info-circle-fill"></i>
                            <span>{{ __('shipment_tracking.no_info') }}</span>
                        </div>
                    @else
                        <div class="mt-4 d-flex flex-column gap-4">
                            @foreach ($results as $tracking)
                                @continue(empty($tracking['Value']))
                                @php $firstEntry = $tracking['Value'][0] ?? null; @endphp

                                <div class="shipment-group">
                                    <div class="shipment-group-header">
                                        <i class="bi bi-box-seam"></i>
                                        <span>{{ __('shipment_tracking.details') }}</span>
                                        @if ($firstEntry)
                                            <span class="shipment-group-id">{{ $firstEntry['WaybillNumber'] }}</span>
                                        @endif
                                    </div>

                                    <div class="timeline">
                                        @foreach ($tracking['Value'] as $index => $entry)
                                            @php
                                                preg_match('/\/Date\((\d+)(?:[+-]\d+)?\)\//', $entry['UpdateDateTime'], $matches);
                                                $timestamp = isset($matches[1]) ? intval($matches[1]) / 1000 : null;
                                                $date = $timestamp ? \Carbon\Carbon::createFromTimestamp($timestamp)->format('d/m/Y') : '';

                                                $statusKey = $entry['NormalizedStatus'] ?? 'unknown';
                                                $label = __('shipment_status.' . $statusKey);
                                                if ($label === 'shipment_status.' . $statusKey) {
                                                    $label = \App\Traits\TranslateTrait::TranslateText($entry['UpdateDescription']);
                                                }
                                            @endphp
                                            <div class="timeline-item {{ $index === 0 ? 'timeline-item-current' : '' }}">
                                                <div class="timeline-marker"></div>
                                                <div class="timeline-content">
                                                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                                        <span class="badge-status">{{ $label }}</span>
                                                        @if ($date)
                                                            <span class="text-muted small">
                                                                <i class="bi bi-calendar-event"></i> {{ $date }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <p class="mb-0 text-muted small mt-2">
                                                        {{ __('shipment_tracking.number') }}:
                                                        <strong class="text-dark">{{ $entry['WaybillNumber'] }}</strong>
                                                    </p>
                                                    @if (!empty($entry['UpdateLocation']))
                                                        <p class="mb-0 text-muted small">
                                                            <i class="bi bi-geo-alt-fill"></i> {{ $entry['UpdateLocation'] }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <style>
        :root {
            --teal: #008080;
            --teal-dark: #006666;
            --teal-light: #e6f2f2;
            --teal-border: #b3d9d9;
        }

        .tracking-card {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.04);
        }

        .tracking-header {
            background: linear-gradient(135deg, #006666 0%, #008080 55%, #00a3a3 100%);
            color: #fff;
            padding: 2.25rem 2rem 1.75rem;
        }

        .tracking-header h2 {
            color: #fff;
        }

        .tracking-header p {
            color: rgba(255,255,255,0.85) !important;
        }

        .tracking-icon {
            font-size: 2.25rem;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .tracking-body {
            padding: 2rem;
        }

        .text-teal {
            color: var(--teal) !important;
        }

        .shipment-input-group .input-group-text {
            border: 1px solid #dbe6e6;
        }

        .shipment-input-group .form-control {
            border: 1px solid #dbe6e6;
            box-shadow: none;
        }

        .shipment-input-group .form-control:focus {
            border-color: var(--teal);
            box-shadow: none;
        }

        .btn-track {
            background: linear-gradient(135deg, #006666, #008080);
            color: #fff;
            border: none;
            border-radius: 0.75rem;
            padding: 0.85rem 1.5rem;
            font-weight: 600;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            box-shadow: 0 6px 16px rgba(0, 128, 128, 0.3);
        }

        .btn-track:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(0, 128, 128, 0.4);
            color: #fff;
        }

        .btn-track:disabled {
            opacity: 0.7;
        }

        .alert-modern {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.9rem 1.1rem;
            border-radius: 0.75rem;
            font-size: 0.9rem;
        }

        .alert-modern-danger {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .alert-modern-info {
            background: var(--teal-light);
            color: var(--teal-dark);
            border: 1px solid var(--teal-border);
        }

        /* Timeline */
        .timeline {
            position: relative;
            max-height: 420px;
            overflow-y: auto;
            padding-left: 1.75rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0.4rem;
            top: 0.4rem;
            bottom: 0.4rem;
            width: 2px;
            background: #e5e7eb;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 1.25rem;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-marker {
            position: absolute;
            left: -1.75rem;
            top: 0.35rem;
            width: 0.85rem;
            height: 0.85rem;
            border-radius: 50%;
            background: #cbd5e1;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #e5e7eb;
        }

        .timeline-item-current .timeline-marker {
            background: var(--teal);
            box-shadow: 0 0 0 4px rgba(0, 128, 128, 0.15);
        }

        .timeline-content {
            background: #f9fafb;
            border: 1px solid #eef0f3;
            border-radius: 0.75rem;
            padding: 0.9rem 1.1rem;
        }

        .timeline-item-current .timeline-content {
            background: var(--teal-light);
            border-color: var(--teal-border);
        }

        .badge-status {
            background: var(--teal);
            color: #fff;
            font-weight: 600;
            font-size: 0.78rem;
            padding: 0.3rem 0.65rem;
            border-radius: 0.5rem;
            display: inline-block;
        }

        .timeline-item:not(.timeline-item-current) .badge-status {
            background: #6b7280;
        }

        /* Scrollbar */
        .timeline::-webkit-scrollbar {
            width: 6px;
        }

        .timeline::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }
    </style>
</div>


