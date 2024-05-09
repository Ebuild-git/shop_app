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
                                <div class="col text-center" onclick="ShowPostsCatgorie({{ $shopiner->id }})">
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
