<div class="card p-2 position-relative mb-2"
     style="
     @if(auth()->check() && $user->id === auth()->id())
         border: 2px solid #008080; background-color: #f9fbfc;
     @elseif(isset($user->is_pinned) && $user->is_pinned)
         border: 2px solid #FFD700; background-color: #fffef7;
     @endif
     ">
    <div>
        <div class="d-flex justify-content-between">
            <div class="d-flex align-items-center pl-3" style="text-align: left">
                @if ($user->avatar == 'avatar.png' || !$user->avatar)
                    <img src="https://t3.ftcdn.net/jpg/05/00/54/28/360_F_500542898_LpYSy4RGAi95aDim3TLtSgCNUxNlOlcM.jpg"
                         alt="Default Avatar" class="rounded-circle mr-2" width="40" height="40">
                @elseif (!is_null($user->photo_verified_at))
                    <img src="{{ Storage::url($user->avatar) }}"
                         alt="User Avatar" class="rounded-circle mr-2" width="40" height="40">
                @else
                    <img src="https://t3.ftcdn.net/jpg/05/00/54/28/360_F_500542898_LpYSy4RGAi95aDim3TLtSgCNUxNlOlcM.jpg"
                         alt="Default Avatar" class="rounded-circle mr-2" width="40" height="40">
                @endif

                <h4 class="h6 mb-0" style="{{ app()->getLocale() == 'ar' ? 'margin-right: 10px;' : '' }}">
                    <a href="/user/{{ $user->id }}" class="link">
                        {{ $user->username }}
                    </a>
                </h4>
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
                    {{ __('Ventes') }} : {{ $user->total_sales()->count() }}
                </div>
                <div class="col text-center cursor" onclick="ShowPostsCatgorie({{ $user->id }})">
                    <div>
                        <img width="20" height="20" src="/icons/menu.svg" alt="category" />
                    </div>
                    {{ __('Catégories') }} : {{ $user->categoriesWhereUserSell() }}
                </div>
                <div class="col text-center">
                    <a href="/user/{{ $user->id }}">
                        <div>
                            <img width="20" height="20" src="/icons/shopping-en-ligne.svg" alt="external" />
                        </div>
                        {!! \App\Traits\TranslateTrait::TranslateText('Annonces') !!} : {{ $user->voyage_mode ? 0 : $user->ValidatedPosts->count() }}
                    </a>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-2 text-bold note-shopinner-bas">
            <div>
                <b>
                    @php
                        $count = number_format($user->averageRating->average_rating ?? 1);
                        $avis = $user->getReviewsAttribute->count();
                    @endphp
                    <x-Etoiles :count="$count" :avis="$avis" :user="$user"></x-Etoiles>
                    {{ $avis }} {{ __('avis') }}
                </b>
            </div>

            @if ($page == 'shopiners')
                <div>
                    <a href="/user/{{ $user->id }}" class="link">
                        <b>{!! \App\Traits\TranslateTrait::TranslateText('Voir le profil') !!}</b>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
