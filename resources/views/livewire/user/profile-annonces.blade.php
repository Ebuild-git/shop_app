<div class="row">
   {{--  <div class="col-sm-12">
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
    </div> --}}
    @forelse ($posts as $post)
    <div class="col-xl-4 col-sm-4 col-lg-4 col-md-6 col-6">
        <div class="product_grid card b-0">
            <div class="badge bg-success-ps text-white position-absolute ft-regular ab-left text-upper">
                <i class="bi bi-gem"></i> LUXURY
            </div>
            <div class="badge badge-like-post-count position-absolute ab-right text-upper">
                <i class="far fa-heart"></i>
                <span>
                    {{ $post->getLike->count() }}
                </span>
            </div>
            <div class="card-body p-0">
                <div class="shop_thumb position-relative">
                    <a class="card-img-top d-block overflow-hidden" href="/post/{{ $post->id }}"><img
                            class="card-img-top" src="{{ Storage::url($post->photos[0] ?? '') }}"
                            alt="..."></a>
                    <div
                        class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                        <div class="edlio"><a href="#" data-toggle="modal"
                                data-target="#quickview-{{ $post->id }}"
                                class="text-white fs-sm ft-medium">
                                <i class="fas fa-eye mr-1"></i>
                                vue rapide </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer b-0 p-0 pt-2 bg-white">
                <div class="">
                    <div class="text-left">
                        {{ $post->sous_categorie_info->titre }}
                    </div>
                    <div class="text-left">
                        Marque if available {{ $post->id }}
                    </div>
                </div>
                <div class="text-left">
                    <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1">
                        <a href="/post/{{ $post->id }}">
                            {{ $post->titre }}
                        </a>
                    </h5>
                    <div class="elis_rty color">
                        <span class="ft-bold  fs-sm">
                            @livewire('User.prix', ['id_post' => $post->id])
                            DH
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('User.ProductViewModal', ['id_post' => $post->id])
    @empty
        <div class="col-sm-4 mx-auto text-center pt-5 pb-5">
            <img width="80" height="80" src="https://img.icons8.com/dotty/80/018d8d/web-design.png" alt="web-design"/>
            <div class="color col-lg-12" role="alert">
               <b> Aucun article trouvé !</b>
            </div>
        </div>
    @endforelse

</div>
