@extends('User.fixe')
@section('titre', $user->username)
@section('content')
@section('body')

<!-- ======================= Filter Wrap Style 1 ======================== -->
<section class="py-3" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/"><i class="fas fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"> {{ $user->username }} </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- ============================= Filter Wrap ============================== -->


<div class="container pb-3 pt-3" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
    <div class="row">
        <div class="col-sm-4">
            <div>
                <table>
                    <tr>
                        <td>
                            <div class="avatar-shopinner-details">
                                @if ($user->avatar == 'avatar.png' || !$user->avatar)
                                    <img src="https://t3.ftcdn.net/jpg/05/00/54/28/360_F_500542898_LpYSy4RGAi95aDim3TLtSgCNUxNlOlcM.jpg"
                                        alt="Default Avatar" height="80">
                                @elseif (!is_null($user->photo_verified_at))
                                    <img src="{{ Storage::url($user->avatar) }}"
                                        alt="User Avatar" height="80">
                                @else
                                    <img src="https://t3.ftcdn.net/jpg/05/00/54/28/360_F_500542898_LpYSy4RGAi95aDim3TLtSgCNUxNlOlcM.jpg"
                                            alt="Default Avatar" height="80">
                                @endif
                            </div>
                        </td>
                        <td>
                            <h4 class="h6">
                                <a href="/user/{{ $user->id }}" class="h4">
                                    <span class="color">
                                        {{ $user->username }}
                                    </span>
                                </a>
                            </h4>
                            <div>
                                <div>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            {{ trans_choice('messages.avis', $avis, ['count' => $avis]) }}
                                        </div>
                                            @if(auth()->check() && auth()->id() !== $user->id)
                                                <div data-toggle="modal" data-target="#Noter">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <button type="button"
                                                            class="btn-rating-modal {{ $ma_note   >= $i ? 'rating-yellow-color' : 'none' }} ">
                                                            <i class="bi bi-star-fill"></i>
                                                        </button>
                                                    @endfor
                                                </div>
                                            @endif
                                    </div>
                                </div>
                                <div>
                                    <span>
                                        <b>{{ $user->total_sales->count() }}</b> {{ trans_choice('messages.sales', $user->total_sales->count()) }}
                                    </span>
                                    |
                                    <span>
                                        <b>{{ $user->voyage_mode ? 0 : $user->ValidatedPosts->count() }}</b> {{ trans_choice('messages.annonces', $user->ValidatedPosts->count()) }}
                                    </span>
                                    |
                                    <span onclick="ShowPostsCatgorie({{ $user->id }})" class="cusor">
                                        <b>{{ $user->categoriesWhereUserSell() }}</b> {!! \App\Traits\TranslateTrait::TranslateText('Catégories') !!}
                                    </span>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <br>
            <div>
                <p>
                    <i class="bi bi-calendar-check"></i> {{ __('Membre depuis le') }} {{ $user->created_at }}
                    <br>
                    {{-- <i class="bi bi-envelope"></i> {!! \App\Traits\TranslateTrait::TranslateText('Email vérifié') !!} <b> : {{ $user->photo_verified_at ? 'Oui' : 'Non' }}
                    </b> --}}
                </p>
            </div>
        </div>
        <div class="col-sm-8">
            <div>
                <b class="text-black">
                    {{ $posts->count() }} {{ trans_choice('messages.annonces', $posts->count()) }}
                </b>
            </div>
            <div class="row">
                @forelse ($posts as $post)
                <div class="col-xl-4 col-sm-4 col-lg-4 col-md-6 col-6">
                    <div class="product_grid card b-0">
                        <div class="badge-container position-absolute top-0 start-0 d-flex gap-4 mobile-display-luxe"
                            style="z-index: 5; {{ app()->getLocale() === 'ar' ? 'left: 4px; right: auto; text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
                            @if ($post->sell_at)
                            <div class="badge-new badge-danger-new mb-4">
                                {!! \App\Traits\TranslateTrait::TranslateText('Vendu') !!}
                            </div>
                            @endif
                            @if ($post->discountPercentage)
                            <div class="badge-new badge-discount" style="{{ app()->getLocale() === 'ar' ? 'margin-top: -24px;' : '' }}">
                                -{{ $post->discountPercentage }}%
                            </div>
                            @endif
                        </div>

                        <button type="button" class="badge badge-like-post-count btn-favorite-post position-absolute ab-right cusor {{ $post->isFavoritedByUser(Auth::id()) ? 'active' : '' }}"
                            id="post-{{ $post->id }}" data-post-id="{{ $post->id }}"
                            onclick="toggleFavorite({{ $post->id }})">
                        <i class="bi bi-suit-heart-fill"></i>
                        <span class="count">{{ $post->favoris->count() }}</span>
                        </button>
                        <div class="card-body p-0">
                            <div class="shop_thumb position-relative">
                                <a class="card-img-top d-block overflow-hidden" href="/post/{{ $post->id }}"><img
                                        class="card-img-top" src="{{ Storage::url($post->photos[0] ?? '') }}" alt="...">
                                </a>
                            </div>
                        </div>
                        <x-SubCardPost :idPost="$post->id"></x-SubCardPost>
                    </div>
                </div>
                @empty
                <div class="col-sm-4 mx-auto text-center pt-5 pb-5">
                    <img width="80" height="80" src="/icons/web-design.png" alt="web-design" />
                    <div class="color col-lg-12" role="alert">
                        <b> {!! \App\Traits\TranslateTrait::TranslateText('Aucun article trouvé !') !!} </b>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>



<!-- Log In Modal -->
<div class="modal fade" id="Noter" tabindex="1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
    <div class="modal-dialog modal-xl login-pop-form" role="document">
        <div class="modal-content" id="loginmodal">
            <div class="modal-headers">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="ti-close"></span>
                </button>
            </div>
            <div class="modal-body p-5">
                <div class="text-center mb-4">
                    <h4 class="m-0 ft-regular">
                            {!! __('Noter le SHOP') !!}
                    </h4>
                </div>
                @livewire('User.Rating',['id_user'=>$user->id])
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->


@endsection
