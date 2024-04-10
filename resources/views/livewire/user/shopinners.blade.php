<div>
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
                                            {{ '@' . $shopiner->username }}
                                            @if ($shopiner->certifier == 'oui')
                                                <i class="bi bi-patch-check-fill small" style="color: #28c76f;"></i>
                                            @endif
                                        </a>
                                    </h4>
                                </div>
                                <div>
                                    publications :{{ $shopiner->GetPosts->count() }} <br>
                                    Total des ventes :
                                    {{ $shopiner->total_sales }}
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
                        <div class="d-flex justify-content-between mt-2 note-shopinner-bas">
                            <div>
                                <i class="bi bi-star-fill" style="color: #ffb74e;"></i>
                                {{ number_format($shopiner->averageRating->average_rating ?? 0, 1) }}
                            </div>
                            <div>
                                
                            </div>
                            <div data-toggle="modal" data-target="#login{{ $shopiner->id }}">
                                <i class="bi bi-hdd-stack"></i>
                                Catégories
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
                                    Top des Catégories vendus !
                                </h2>
                                <h4 class="h6 color">
                                    Par : {{ '@' . $shopiner->username }}
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
        @endforelse
    </div>
</div>
