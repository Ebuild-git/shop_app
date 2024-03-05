<div class="row">
    <div class="col-sm-3">
        <div class="p-3 card">
            <form wire:submit="filtrer" class="">
                <h5 class="text-muted">
                    <i class="bi bi-filter-left"></i> Filtres Avancés
                </h5>
                <hr>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text input-group-text-ps" >
                            <i class="bi bi-search"></i>
                        </span>
                    </div>
                    <input type="text" wire:model="key" class="form-control border-left-none shadow-none"
                        placeholder="Mot clé">
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text input-group-text-ps" >
                                    <i class="bi bi-funnel"></i>
                                </span>
                            </div>
                            <select wire:model="gouvernorat"  wire:model="gouvernorat" class="form-control shadow-none border-left-none">
                                <option value="">Gouvernorat</option>
                                @foreach ($liste_gouvernorat as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-6">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text input-group-text-ps" >
                                    <i class="bi bi-funnel"></i>
                                </span>
                            </div>
                            <input type="number" class="form-control shadow-none border-left-none"
                                placeholder="Prix Minimum" wire:model="prix_minimun">
                        </div>
                    </div>
                    <div class="col-sm-6 col-6">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text input-group-text-ps" >
                                    <i class="bi bi-funnel"></i>
                                </span>
                            </div>
                            <input type="number" class="form-control shadow-none border-left-none"
                                placeholder="Prix Maximun" wire:model="prix_maximun">
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text input-group-text-ps" >
                            <i class="bi bi-funnel"></i>
                        </span>
                    </div>
                    <select wire:model="categorie" wire:model="categorie" class="form-control shadow-none border-left-none">
                        <option value="">Catégories</option>
                        @foreach ($liste_categories as $item)
                            <option value="{{ $item->id }}">{{ $item->titre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <div class="input-group mb-3 ">
                        <div class="input-group-prepend">
                            <span class="input-group-text input-group-text-ps" >
                                <i class="bi bi-sort-alpha-down"></i>
                            </span>
                        </div>
                        <select wire:model="ordre" class="form-control shadow-none border-left-none">
                            <option value="">Ordre de publication</option>
                            <option value="Asc">Des plus ancients</option>
                            <option value="Desc">Des plus récents</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <button type="submit" class="btn bg-red w-100">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                                wire:loading></span>
                            Filtrer
                            <i class="bi bi-funnel"></i>
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="rerst" wire:click="reset_form" class="btn btn-secondary disabled w-100">
                            <i class="bi bi-x-lg"></i>
                            Vider
                        </button>
                    </div>
                </div>
            </form>
            <hr>
            <div class="row">
                @foreach ($liste_categories as $item)
                    <div class="col-sm-4 col-4">
                        <div class="div-cat-shop-item">
                            <img src="{{ Storage::url($item->icon) }}" >
                            <div class="small">
                                {{ strlen($item->titre) > 10 ? substr($item->titre, 0, 10) . '...' : $item->titre }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-sm-9">
        <div class="card p-2">
            <div>
                <span class="h5">
                    Vous avez a votre disposition plus de <span class="color-orange">{{ $total }}</span>
                    disponibles.
                </span>
            </div>
            <br>
            <div class="row">

                @forelse ($posts as $item)
                    <x-CardPost :post="$item" :class="'col-6 col-md-2 col-lg-3 col-xl-2 pb-3'"></x-CardPost>
                @empty
                    <div class="alert alert-danger col-lg-12" role="alert">
                        Aucun article trouvé !
                    </div>
                @endforelse
            </div>
            <!-- Pagination -->
            <nav aria-label="...">
                <ul class="pagination justify-content-center">
                    {{ $posts->links('pagination::bootstrap-5') }}
                </ul>
            </nav>
        </div>
    </div>
</div>
