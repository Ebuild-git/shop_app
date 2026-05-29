<div>
     <div class="row" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
        <div class="col-md-12">
            <div class="filter-card">

                <!-- Search -->
                <div class="filter-item search-box">
                    <i class="fa fa-search"></i>

                    <input type="text"
                        wire:model.live="key"
                        class="filter-input"
                        placeholder="{!! \App\Traits\TranslateTrait::TranslateText('Trouvez votre shopiner idéal') !!}">
                </div>

                <!-- Rating -->
                <div class="filter-item select-box">
                    <i class="fa fa-star"></i>

                    <select wire:model.live="rating" class="filter-select">
                        <option value="">
                            {!! \App\Traits\TranslateTrait::TranslateText('Filtrer par avis') !!}
                        </option>

                        <option value="1">★ 1 {!! \App\Traits\TranslateTrait::TranslateText('Étoile et plus') !!}</option>
                        <option value="2">★★ 2 {!! \App\Traits\TranslateTrait::TranslateText('Étoiles et plus') !!}</option>
                        <option value="3">★★★ 3 {!! \App\Traits\TranslateTrait::TranslateText('Étoiles et plus') !!}</option>
                        <option value="4">★★★★ 4 {!! \App\Traits\TranslateTrait::TranslateText('Étoiles et plus') !!}</option>
                        <option value="5">★★★★★ 5 {!! \App\Traits\TranslateTrait::TranslateText('Étoiles') !!}</option>
                    </select>
                </div>

            </div>
        </div>
    </div>
     <br>

     <div class="row" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
         @forelse ($shopiners as $key => $shopiner)
             <div class="col-sm-4">
                 @include('components.CardShopinner', ['user' => $shopiner, 'page' => 'shopiners'])
             </div>
         @empty
             <p class="color text-center p-5">
                 {!! \App\Traits\TranslateTrait::TranslateText('Aucun shopiner trouvé pour le moment') !!}
                 @if ($key)
                     {!! \App\Traits\TranslateTrait::TranslateText('avec le mot') !!} " <b> {{ $key }} </b> "
                 @endif .
             </p>
         @endforelse
     </div>
</div>
