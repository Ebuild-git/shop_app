<div>
    <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div class="card-title mb-0">
                <h5 class="m-0 me-2">Liste Des Commandes</h5>
            </div>
        </div>
        <div class="card-body">

           <!-- START: Filters -->
            <div class="row mb-4 align-items-end">
                <div class="col-md-4">
                    <label for="regionFilter" class="form-label">Filtrer par région</label>
                    <select wire:model.defer="region_id" class="form-select form-select-sm" id="regionFilter">
                        <option value="">Toutes les régions</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="dateFilter" class="form-label">Filtrer par date</label>
                    <input wire:model.defer="date" type="date" class="form-control form-control-sm" id="dateFilter">
                </div>

                <div class="col-md-5 d-flex gap-2">
                    <div class="mt-3">
                        <button wire:click="applyFilters" class="btn btn-sm btn-primary">Appliquer</button>
                    </div>
                    <div class="mt-3">
                        <button wire:click="resetFilters" class="btn btn-sm btn-outline-secondary">Réinitialiser</button>
                    </div>
                </div>
            </div>
            <!-- END: Filters -->

            <div class="table-responsive">
                <table class="table w-100 table-custom">
                    <thead class="th-white">
                        <tr>
                            <th>Vendeur</th>
                            <th>Acheteur</th>
                            <th>Article</th>
                            <th>ID Expédition (Aramex)</th>
                            <th>Frais Livraison</th>
                            <th>État</th>
                            <th>Statut</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($commandes as $commande)
                            <tr>
                                <td>
                                    <a href="/admin/client/{{ $commande->vendor->id }}/view">
                                            {{ $commande->vendor->username }}
                                    </a>
                                    <br>
                                    <small><b class="text-color2">Région:</b> {{ $commande->vendor->region_info->nom  ?? "/"}}</small>
                                    <div>
                                        <span class="message-style" onclick="OpenModalMessage('{{ $commande->vendor->id }}','{{ $commande->vendor->username }}', '{{ $commande->post->titre }}', '{{ $commande->post->id }}', '{{ config('app.url').Storage::url($commande->post->photos[0]) }}')">
                                            <i class="bi bi-chat-left-text-fill" style="margin-right: 5px;"></i> Message
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <a href="/admin/client/{{ $commande->buyer->id }}/view">
                                            {{ $commande->buyer->username }}
                                    </a>
                                    <br>
                                    <small><b class="text-color2">Région:</b> {{ $commande->buyer->region_info->nom }}</small>
                                    <div>
                                        <span class="message-style" onclick="OpenModalMessage('{{ $commande->buyer->id }}','{{ $commande->buyer->username }}', '{{ $commande->post->titre }}', '{{ $commande->post->id }}', '{{ config('app.url').Storage::url($commande->post->photos[0]) }}')">
                                            <i class="bi bi-chat-left-text-fill" style="margin-right: 5px;"></i> Message
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <a href="/admin/publication/{{ $commande->post->id }}/view">
                                        {{ $commande->post->titre }}
                                    </a>
                                </td>
                                <td>{{ $commande->shipment_id ?? '—' }}</td>
                                <td>{{ $commande->frais_livraison }} <sup>DH</sup></td>
                                <td>
                                    <span class="badge-etat
                                        @if($commande->etat === 'attente') etat-attente
                                        @elseif($commande->etat === 'confirmé') etat-confirmé
                                        @elseif($commande->etat === 'annulé') etat-annulé
                                        @endif">
                                        {{ $commande->etat }}
                                    </span>
                                </td>
                                <td><span class="statut-badge">{{ $commande->statut }}</span></td>
                                <td>{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="p-3">
                                        Aucune commande trouvée
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $commandes->links('pagination::bootstrap-4') }}</div>
        </div>
    </div>
</div>
