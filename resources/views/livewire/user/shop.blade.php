<div class="row">
    <div class="col-sm-3">
        <form wire:submit="filtrer">
            <h5>Filtres</h5>

            <hr>
            <input type="text" wire:model="key" class="form-control shadow-none" placeholder="Mot clé">
            <div class="row">
                <div class="col-sm-6 col-6">
                    Ville
                    <select wire:model="gouvernorat" class="form-control">
                        <option value=""></option>
                        @foreach ($liste_gouvernorat as $item)
                            <option value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-6">
                    Gouvernorat
                    <select wire:model="gouvernorat" class="form-control">
                        <option value=""></option>
                        @foreach ($liste_gouvernorat as $item)
                            <option value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-6">
                    Prix Minimum
                    <input type="number" class="form-control" wire:model="prix_minimun">
                </div>
                <div class="col-sm-6 col-6">
                    Prix Maximun
                    <input type="number" class="form-control" wire:model="prix_maximun">
                </div>
            </div>
            Catégories :
            <select wire:model="categorie" class="form-control">
                <option value=""></option>
                @foreach ($liste_categories as $item)
                    <option value="{{ $item->id }}">{{ $item->titre }}</option>
                @endforeach
            </select>
            <div class="mb-3">
                Ordre de publication
                <select wire:model="ordre" class="form-control">
                    <option value="Asc">Des plus ancients</option>
                    <option value="Desc">Des plus récents</option>
                </select>
            </div>
            <br>
            <button type="submit" class="btn bg-red w-100">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading></span>
                Filtrer
                <i class="bi bi-funnel"></i>
            </button>
        </form>

    </div>
    <div class="col-sm-9">
        <br>
        <div class="row">

            @forelse ($posts as $item)
                <x-CardPost :post="$item" :class="'col-12 col-md-2 col-lg-4 col-xl-2'"></x-CardPost>
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
