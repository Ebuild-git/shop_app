@if ($optionsArray)
    <div class="card p-2 mb-3">
        <p>
            Veuillez choisir les propriétés pour une recherche plus exact.
        </p>
        <div class="d-flex align-content-start flex-wrap ">
            @foreach ($ArrayProprietes as $key => $item)
                <div class="d-flex align-content-start flex-wrap list-proprietes">
                    <button type="button" class="card p-1 m-1 card-hover-titre cusor" data-key="{{ $item['nom'] }}"
                        onclick="show_attribut({{ $key }})" wire:click="set_key('{{ $item['nom'] }}')">
                        {{ $item['nom'] }}
                    </button>
                    @foreach ($item['options'] ?? [] as $option)
                        <div class="card p-1 m-1 card-hover-prroposition cusor d-none attribut attribut-{{ $item['nom'] }}"
                            onclick="filtre_propriete('{{ $option['nom'] }}')">
                            {{ $option['nom'] }}
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endif
