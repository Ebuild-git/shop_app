<div class="row">
    @forelse ($posts as $post)
    <div class="col-xl-3 col-sm-3 col-lg-3 col-md-6 col-6">
        <div class="product_grid card b-0">
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
            <x-SubCardPost :idPost="$post->id"></x-SubCardPost>
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
