<div>
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-end">
                <div class="me-2">
                    <input type="text" wire:model.live="key" class="form-control" placeholder="Trouvez votre shopiner idéal">
                </div>
                <div>
                    <select wire:model.live="rating" class="form-control custom-select">
                        <option value="">Filtrer par avis</option>
                        <option value="1">★ 1 Étoile et plus</option>
                        <option value="2">★★ 2 Étoiles et plus</option>
                        <option value="3">★★★ 3 Étoiles et plus</option>
                        <option value="4">★★★★ 4 Étoiles et plus</option>
                        <option value="5">★★★★★ 5 Étoiles</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="row">
        @forelse ($shopiners as $key=>$shopiner)
            <div class="col-sm-4">
                @include('components.CardShopinner', ['user' => $shopiner, 'page' => 'shopiners'])
            </div>
        @empty
            <p class="color text-center p-5">
                Aucun shopiner trouvé pour le moment
                @if ($key)
                    avec le mot " <b> {{ $key }} </b> "
                @endif .
            </p>
        @endforelse
    </div>
</div>
