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
                        <button class="collapse-toggle" data-target="#types{{ $propriete->id }}">
                            {{ $propriete->nom }}
                            <span class="collapse-icon">
                                <i class="bi bi-plus-lg"></i> <!-- Initial icon as plus -->
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
                                @foreach (json_decode($propriete->options ?? []) as $option)
                                    <button class="btn btn-sm w-1" id="btn-option-{{ str_replace(' ', '', $option) }}"
                                        onclick="filtre_propriete('{{ $propriete->nom }}','{{ $option }}')">
                                        {{ $option }}
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





<!-- Mobile-specific Filter Design -->
{{-- <div class="mobile-only">
    <div class="widget-boxed-body collapse" id="types{{ $propriete->id }}" data-parent="#types{{ $propriete->id }}">
        <div class="side-list no-border">
            <!-- Single Filter Card -->
            <div class="filter-options-container">
                @if (Str::lower($propriete->type) == 'color')
                    <!-- Color Options as List with Checkboxes -->
                    <ul class="color-options-list mobile-only">
                        @foreach ($colors as $color)
                            <li class="color-option-item">
                                <input type="checkbox" id="color-{{ $color['code'] }}" name="color" value="{{ $color['code'] }}"
                                    onchange="filtre_propriete_color('{{ $propriete->nom }}', '{{ $color['code'] }}', '{{ $color['nom'] }}')">
                                <label for="color-{{ $color['code'] }}" class="color-label">
                                    <span class="color-indicator" style="background-color: {{ $color['code'] }};"></span>
                                    {{ $color['nom'] }}
                                </label>
                            </li>
                        @endforeach
                    </ul>
                @else
                    @if ($propriete->options)
                        <!-- Option Filters as List with Checkboxes -->
                        <ul class="option-filters-list">
                            @foreach (json_decode($propriete->options ?? []) as $option)
                                <li class="option-filter-item">
                                    <input type="checkbox" id="option-{{ str_replace(' ', '', $option) }}" name="option" value="{{ $option }}"
                                        onchange="filtre_propriete('{{ $propriete->nom }}', '{{ $option }}')">
                                    <label for="option-{{ str_replace(' ', '', $option) }}">{{ $option }}</label>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div> --}}


