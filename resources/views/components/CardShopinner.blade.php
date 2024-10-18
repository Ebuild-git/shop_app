<div class="card p-2 position-relative mb-2">
    <div>
        <div class="d-flex justify-content-between">
            <div class="pl-3" style="text-align: left">
                <div>
                    <h4 class="h6">
                        <a href="/user/{{ $user->id }}" class="link">
                            {{ $user->username }}
                        </a>
                    </h4>
                </div>
            </div>
            @if ($page == 'shopiners')
                <div style="text-align: right;">
                    @auth
                        @if ($user->id !== auth()->id())  <!-- Check if the user is not the auth user -->
                            @if (auth()->user()->pings()->where('pined', $user->id)->exists())
                                <button wire:click="ping( {{ $user->id }} )" class="btn-ping-shopinner cursor">
                                    <img src="/icons/icons8.png" height="20" width="20" alt="">
                                </button>
                            @else
                                <button wire:click="ping( {{ $user->id }} )" class="btn-ping-shopinner cursor">
                                    <img src="/icons/icons9.png" height="20" width="20" alt="">
                                </button>
                            @endif
                        @endif
                    @endauth
                </div>
            @endif

        </div>
        <div>
            <div class="row">
                <div class="col text-center">
                    <div>
                        <img width="20" height="20" src="/icons/sac-de-courses.svg" alt="sale" />
                    </div>
                    Ventes : {{ $user->total_sales->count() }}
                </div>
                <div class="col text-center cusor" onclick="ShowPostsCatgorie({{ $user->id }})">
                    <div>
                        <img width="20" height="20" src="/icons/menu.svg" alt="category" />
                    </div>
                    Catégories : {{ $user->categoriesWhereUserSell() }}
                </div>
                <div class="col text-center">
                    <a href="/user/{{ $user->id }}">
                        <div>
                            <img width="20" height="20" src="/icons/shopping-en-ligne.svg" alt="external" />
                        </div>
                        Annonces :
                        {{ $user->voyage_mode ? 0 : $user->GetPosts->count() }}
                    </a>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-2  text-bold note-shopinner-bas">
            <div>
                <b>
                    @php
                        $count = number_format($user->averageRating->average_rating ?? 1);
                        $avis = $user->getReviewsAttribute->count();
                    @endphp
                    <x-Etoiles :count="$count" :avis="$avis"></x-Etoiles>
                    {{ $avis }}
                    Avis
                </b>
            </div>
            {{-- Afficher le lien pour voirle profil uniquement si on est dans la page shopinner --}}
            @if ($page == 'shopiners')
                <div>
                    <a href="/user/{{ $user->id }}" class="link">
                        <b>Voir le profil</b>
                    </a>
                </div>
            @endif

        </div>

    </div>
</div>
