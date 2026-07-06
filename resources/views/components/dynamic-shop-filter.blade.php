{{-- <div>
    @foreach ($selected_sous_categorie->proprietes as $id_propriete)
        @if ($show_option)

            @php
                $propriete = DB::table('proprietes')
                    ->whereIn(DB::raw('LOWER(RTRIM(REPLACE(nom, "\t", "")))'), $show_option)
                    ->where('id', $id_propriete)
                    ->first();
            @endphp


        @else
            @php
                $propriete = DB::table('proprietes')->where('id', $id_propriete)->first();
            @endphp
        @endif

        @if ($propriete)

            <div class="single_search_boxed">
                <div class="widget-boxed-header">
                    <h4>
                        <button class="collapse-toggle" data-target="#types{{ $propriete->id }}">
                            {{ __($propriete->nom) }}
                            <span class="collapse-icon">
                                <i class="bi bi-plus-lg"></i>
                            </span>
                        </button>
                    </h4>
                </div>
                <div class="widget-boxed-body collapse-content" id="types{{ $propriete->id }}">
                    <div class="color-container">
                        @if (Str::lower($propriete->type) == 'color')
                            <div class="color-grid">
                                @foreach ($colors as $color)
                                    <button
                                        onclick="filtre_propriete_color('{{ $propriete->nom }}','{{ $color['code'] }}','{{ $color['nom'] }}')"
                                        class="color-button">
                                        <div class="color-shop-filtre"
                                            style="background-color: {{ $color['code'] }};"></div>
                                    </button>
                                @endforeach
                            </div>
                        @else
                            @if ($propriete->options)
                                @foreach (json_decode($propriete->options ?? []) as $option)
                                    <button class="btn btn-sm w-1" id="btn-option-{{ str_replace(' ', '', $option) }}"
                                        onclick="filtre_propriete('{{ $propriete->nom }}','{{ $option }}')">
                                        {{ __($option) }}
                                    </button>
                                @endforeach
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>


 --}}
<div>
    @foreach ($selected_sous_categorie->proprietes as $id_propriete)
        @if ($show_option)
            @php
                $propriete = DB::table('proprietes')
                    ->whereIn(DB::raw('LOWER(RTRIM(REPLACE(nom, "\t", "")))'), $show_option)
                    ->where('id', $id_propriete)
                    ->first();
            @endphp
        @else
            @php
                $propriete = DB::table('proprietes')->where('id', $id_propriete)->first();
            @endphp
        @endif

        @if ($propriete)
            @php
                $locale = app()->getLocale();

                $proprieteLabel = match ($locale) {
                    'en' => $propriete->nom_en ?: $propriete->nom,
                    'ar' => $propriete->nom_ar ?: $propriete->nom,
                    default => $propriete->nom,
                };

                $localizedOptions = collect(json_decode($propriete->options ?? '[]', true) ?? [])
                    ->map(function ($option) use ($locale) {
                        if (is_array($option)) {
                            $value = $option['value'] ?? ($option['titre'] ?? '');
                            $label = match ($locale) {
                                'en' => $option['title_en'] ?: ($option['titre'] ?? $value),
                                'ar' => $option['title_ar'] ?: ($option['titre'] ?? $value),
                                default => $option['titre'] ?? $value,
                            };

                            return ['value' => $value, 'label' => $label];
                        }

                        // legacy plain-string option
                        return ['value' => $option, 'label' => $option];
                    });
            @endphp

            <!-- Single Option -->
            <div class="single_search_boxed">
                <div class="widget-boxed-header">
                    <h4>
                        <button class="collapse-toggle" data-target="#types{{ $propriete->id }}">
                            {{ $proprieteLabel }}
                            <span class="collapse-icon">
                                <i class="bi bi-plus-lg"></i>
                            </span>
                        </button>
                    </h4>
                </div>
                <div class="widget-boxed-body collapse-content" id="types{{ $propriete->id }}">
                    <div class="color-container">
                        @if (Str::lower($propriete->type) == 'color')
                            <div class="color-grid">
                                @foreach ($colors as $color)
                                    <button
                                        onclick="filtre_propriete_color('{{ $propriete->nom }}','{{ $color['code'] }}','{{ $color['nom'] }}')"
                                        class="color-button">
                                        <div class="color-shop-filtre"
                                            style="background-color: {{ $color['code'] }};"></div>
                                        {{-- <span class="color-name">{{ $color['nom'] }}</span> --}}
                                    </button>
                                @endforeach
                            </div>
                        @else
                            @if ($propriete->options)
                                @foreach ($localizedOptions as $option)
                                    <button class="btn btn-sm w-1" id="btn-option-{{ str_replace(' ', '', $option['value']) }}"
                                        onclick="filtre_propriete('{{ $propriete->nom }}','{{ $option['value'] }}')">
                                        {{ $option['label'] }}
                                    </button>
                                @endforeach
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>
