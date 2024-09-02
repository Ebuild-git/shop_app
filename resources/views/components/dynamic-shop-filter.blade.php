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
                        <a href="#types{{ $propriete->id }}" data-toggle="collapse" class="collapsed" aria-expanded="false"
                            role="button">
                            {{ $propriete->nom }}
                        </a>
                    </h4>
                </div>
                <div class="widget-boxed-body collapse desktop-only" id="types{{ $propriete->id }}"
                    data-parent="#types{{ $propriete->id }}">
                    <div class="side-list no-border">

                        <!-- Single Filter Card -->
                        <div>
                            @if (Str::lower($propriete->type) == 'color')
                                <div class="row">
                                    @foreach ($colors as $color)
                                        <button
                                            onclick="filtre_propriete_color('{{ $propriete->nom }}','{{ $color['code'] }}','{{ $color['nom'] }}')"
                                            class="btn btn-sm m-1 col-5 mx-auto d-flex justify-content-start">
                                            <div class="color-shop-filtre"
                                                style="background-color: {{ $color['code'] }};"></div>
                                            {{ $color['nom'] }}
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
            </div>
        @endif
    @endforeach
</div>


<!-- Mobile-specific Filter Design -->
<div class="mobile-only">
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
</div>

<style>
    /* Mobile-specific styles */
@media (max-width: 576px) {
    .widget-boxed-body {
        padding: 0;
    }

    .filter-options-container {
        padding: 0.5rem;
    }

    .color-options-list {
        max-height: 150px; /* Adjust based on the height of 5 items */
        overflow-y: auto;
        padding: 0;
        margin: 0;
        list-style-type: none;
    }
    .option-filters-list {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .color-option-item {
        display: flex;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #eef0f5;
    }
    .option-filter-item {
        display: flex;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #eef0f5;
    }

    .color-label {
        margin-left: 8px;
        font-size: 14px;
        display: flex;
        align-items: center;
    }
    .option-filter-item label {
        margin-left: 8px;
        font-size: 14px;
        display: flex;
        align-items: center;
    }

    .color-indicator {
        height: 20px;
        width: 20px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }
}
</style>
