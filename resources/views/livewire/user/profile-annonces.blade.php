<div class="row">
    <div class="col-sm-12">
        <form wire:submit="filtrer">
            <div class="input-group mb-3">
                <input wire:model="key" placeholder="Mot clé" class="form-control">
                <select wire:model="order" class="form-control">
                    <option value=""></option>
                    <option value="Asc">de A à Z</option>
                    <option value="Des">de Z à A</option>
                </select>
                <div class="input-group-prepend">
                    <button type="submit" class="btn bg-red">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                            wire:loading></span>Filtrer
                        <i class="bi bi-sort-alpha-up"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
    @forelse ($posts as $item)
        <x-CardPost :post="$item" :class="'col-6 col-md-2 col-lg-4 col-xl-3 pb-3'"></x-CardPost>
    @empty
        <div class="alert alert-danger col-lg-12" role="alert">
            Aucun article trouvé !
        </div>
    @endforelse

</div>
