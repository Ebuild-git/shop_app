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
                <div class="widget-boxed-body collapse" id="types{{ $propriete->id }}"
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
                                        <button class="btn btn-sm w-1" id="btn-option-{{ $option }}"
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
