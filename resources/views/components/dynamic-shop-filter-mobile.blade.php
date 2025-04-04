<div>
    @foreach ($selected_sous_categorie->proprietes as $id_propriete)
        @if ($show_option)
            @php
                $propriete = DB::table('proprietes')
                    ->whereIn(DB::raw('LOWER(nom)'), $show_option)
                    ->where('id', $id_propriete)
                    ->first();
            @endphp
        @else
            @php
                $propriete = DB::table('proprietes')->where('id', $id_propriete)->first();
            @endphp
        @endif

        @if ($propriete)

            <!-- Single Option -->
            <div class="single_search_boxed">
                <div class="widget-boxed-header">
                    <h4>
                        @php
                            $unique_id = 'collapse-' . md5($propriete->id . $propriete->nom);
                        @endphp
                        <button class="collapse-toggle" data-target="#{{ $unique_id }}">
                            {{ \App\Traits\TranslateTrait::TranslateText($propriete->nom) }}
                            <span class="collapse-icon">
                                <i class="bi bi-plus-lg"></i>
                            </span>
                        </button>
                    </h4>
                </div>
                <div class="widget-boxed-body collapse-content" id="{{ $unique_id }}">
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
                                @foreach (json_decode($propriete->options ?? []) as $option)
                                    <button class="btn btn-sm w-1" id="btn-option-{{ str_replace(' ', '', $option) }}"
                                        onclick="filtre_propriete('{{ $propriete->nom }}','{{ $option }}')">

                                        {{ \App\Traits\TranslateTrait::TranslateText($option) }}
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
