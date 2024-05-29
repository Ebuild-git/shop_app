<div>
    <div class="row">
        <div class="col-sm-8 ">
        </div>
        <div class="col-sm-4 ">
            <input type="text" wire:model.live="key" class="form-control" placeholder="Recherche d'un shopiner">
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
