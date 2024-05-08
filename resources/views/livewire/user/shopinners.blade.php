<div>
    <div class="row">
        <div class="col-sm-8 ">
        </div>
        <div class="col-sm-4 ">
            <input type="text" wire:model.live="key" class="form-control" placeholder="Recherche d'un shopiner">
        </div>
    </div>

    <br>

    <div class="row">
        @forelse ($shopiners as $key=>$shopiner)
            <div class="col-sm-4">
                <div class="card p-2 position-relative">
                    <div>
                        <div class="d-flex justify-content-between">
                            <div class="pl-3" style="text-align: left">
                                <div>
                                    <h4 class="h6">
                                        <a href="/user/{{ $shopiner->id }}" class="link">
                                            {{ $shopiner->username }}
                                        </a>
                                    </h4>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                @auth
                                    @if (auth()->user()->pings()->where('pined', $shopiner->id)->exists())
                                        <button wire:click="ping( {{ $shopiner->id }} )" class="btn-ping-shopinner">
                                            <img src="/icons/icons8.png" height="20" width="20" alt="">
                                        </button>
                                    @else
                                        <button wire:click="ping( {{ $shopiner->id }} )" class="btn-ping-shopinner">
                                            <img src="/icons/icons9.png" height="20" width="20" alt="">
                                        </button>
                                    @endif

                                @endauth
                            </div>
                        </div>
                        <div>
                            <div class="row">
                                <div class="col text-center">
                                    <div>
                                        <img width="20" height="20" src="https://img.icons8.com/wired/20/008080/sale.png" alt="sale"/>
                                    </div>
                                    Ventes : {{ $shopiner->total_sales ?? 0}}
                                </div>
                                <div class="col text-center" data-toggle="modal" data-target="#login{{ $shopiner->id }}"> 
                                    <div >
                                        <img width="20" height="20" src="https://img.icons8.com/quill/20/008080/category.png" alt="category"/>
                                    </div>
                                    Catégories : {{ $shopiner->categoriesWhereUserPosted->count() }}
                                </div>
                                <div class="col text-center">
                                    <div >
                                        <img width="20" height="20" src="https://img.icons8.com/external-outline-design-circle/20/008080/external-46-business-and-investment-outline-design-circle.png" alt="external-46-business-and-investment-outline-design-circle"/>
                                    </div>
                                    Annonces : {{ $shopiner->GetPosts->count() }}
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-2  text-bold note-shopinner-bas">
                            <div>
                                <b>
                                    <i class="bi bi-star-fill" style="color: #ffb74e;"></i>
                                {{ number_format($shopiner->averageRating->average_rating ?? 0, 1) }}
                                Avis
                                </b>
                            </div>
                            <div>

                            </div>
                            <div >
                                <a href="/user/{{ $shopiner->id }}" class="link">
                                    <b>Voir le profil</b>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Log In Modal -->
            <div class="modal fade" id="login{{ $shopiner->id }}" tabindex="1" role="dialog"
                aria-labelledby="loginmodal" aria-hidden="true">
                <div class="modal-dialog modal-xl login-pop-form" role="document">
                    <div class="modal-content" id="loginmodal">
                        <div class="modal-headers">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span class="ti-close"></span>
                            </button>
                        </div>

                        <div class="modal-body p-5">
                            <div class="text-center mb-4">
                                <h2 class=" h5">
                                    Catégories vendus !
                                </h2>
                                <h4 class="h6 color">
                                    Par : {{  $shopiner->username }}
                                </h4>
                            </div>
                            <hr>
                            @forelse ($shopiner->categoriesWhereUserPosted as $item)
                                <i class="bi bi-arrow-right"></i>
                                {{ $item->titre }} <br>
                            @empty
                            @endforelse

                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal -->

        @empty
            <p class="color text-center p-5">
                Aucun shopiner trouvé pour le moment
                @if ($key)
                    avec le mot " <b> {{ $key }} </b> "
                @endif .
            </p>
        @endforelse
    </div>
</div>
