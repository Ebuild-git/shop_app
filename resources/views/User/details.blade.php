@extends('User.fixe')
@section('titre', $post->titre)
@section('body')


    <!-- ======================= Top Breadcrubms ======================== -->
    <div class="gray py-3" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
        <div class="container">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/"><i class="fas fa-home"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{ route('shop') }}">
                                    {{ \App\Traits\TranslateTrait::TranslateText('Catégories') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a
                                    href="{{ route('shop') }}?id_categorie={{ $post->sous_categorie_info->categorie->id ?? '' }}">

                                    {{ \App\Traits\TranslateTrait::TranslateText($post->sous_categorie_info->categorie->titre ?? '' ) }}
                                    @if($post->sous_categorie_info->categorie->luxury)
                                    <i class="bi bi-gem small color"></i>
                                    @endif
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('shop') }}?id_categorie={{ $post->sous_categorie_info->categorie->id ?? '' }}&selected_sous_categorie={{ $post->sous_categorie_info->id ?? '' }}">
                                    <b class="color">
                                        {{ \App\Traits\TranslateTrait::TranslateText($post->sous_categorie_info->titre) }}
                                    </b>
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ======================= Top Breadcrubms ======================== -->

    <!-- ======================= Product Detail ======================== -->
    <section class="middle">
        <div class="container">
            <div class="row" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
                <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12">
                    <div>
                        <div class="row">
                            <div class="col-2 p-1">
                                @foreach ($post->photos as $photo)
                                    <div class="gallery-image-details cusor"
                                        onclick="change_principal_image('{{ Storage::url($photo) }}')">
                                        <img src="{{ Storage::url($photo) }}" alt=""
                                            style="width: 100% !important;">
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-10 p-1 ">

                                <div class="col-10 p-1 position-relative">
                                    <figure class="zoom w-100 position-relative" id="figure"
                                        onmousemove="zoom(event)"
                                        style="background-image: url({{ Storage::url($post->photos[0] ?? '') }}); background-size: 200%; background-repeat: no-repeat; background-position: center;">
                                        <img src="{{ Storage::url($post->photos[0] ?? '') }}" id="imgPrincipale" class="w-100 sp-current-big" alt="image" />
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>




                    <div class="shopiner-heading">
                        <hr>
                        <h5 style="font-size: 20px; margin-top:20px;">
                            <b>{!! __('title') !!}</b>
                        </h5>
                        @php
                            $count = number_format($user->averageRating->average_rating ?? 1);
                            $avis = $user->getReviewsAttribute->count();
                        @endphp

                        <div style="margin-top:25px;">
                            <table>
                                <tr>
                                    <td>
                                        <div class="avatar-shopinner-details">
                                            @if ($user->avatar == 'avatar.png' || !$user->avatar)
                                                <img src="https://t3.ftcdn.net/jpg/05/00/54/28/360_F_500542898_LpYSy4RGAi95aDim3TLtSgCNUxNlOlcM.jpg"
                                                    alt="Default Avatar" height="80">
                                            @else
                                                <img src="{{ Storage::url($user->avatar) }}"
                                                    alt="User Avatar" height="80">
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
                                                @php
                                                    $salesCount = $user->total_sales->count();
                                                    $adsCount = $user->voyage_mode ? 0 : $user->ValidatedPosts->count();
                                                    $categoriesCount = $user->categoriesWhereUserSell();
                                                @endphp

                                                <a href="{{ url('/user/' . $user->id) }}" style="color:#707070;">
                                                    <span>
                                                        <b>{{ $salesCount }}</b>
                                                        {{ trans_choice('messages.sales', $salesCount) }}
                                                    </span>
                                                    |
                                                    <span>
                                                        <b>{{ $adsCount }}</b>
                                                        {{ trans_choice('messages.annonces', $adsCount) }}
                                                    </span>
                                                </a>

                                                |
                                                <span onclick="ShowPostsCatgorie({{ $user->id }})" class="cursor">
                                                    <b>{{ $categoriesCount }}</b>
                                                    {{ trans_choice('messages.categories', $categoriesCount) }}
                                                </span>

                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>

                <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12">
                    <div class="prd_details">

                        <div class="prt_01 mb-2 ">
                            <div class="d-flex justify-content-start">
                                @if ($post->sous_categorie_info->categorie->luxury == 1)
                                    <span class="text-success bg-light-success rounded strong px-2 py-1">
                                        <i class="bi bi-gem"></i>
                                        {{ __('SHOPIN_LUXURY')}}
                                    </span>
                                    &nbsp;
                                @endif
                                <a href="{{ route('shop') }}?id_categorie={{ $post->sous_categorie_info->categorie->id }}"
                                    class=" bg-light-info rounded color px-2 py-1 strong"
                                    style="background-color: #0080802d">
                                    <span class="color">
                                        <span class="color">
                                            @switch(app()->getLocale())
                                                @case('en')
                                                    {{ $post->sous_categorie_info->categorie->title_en ?: \App\Traits\TranslateTrait::TranslateText($post->sous_categorie_info->categorie->titre) }}
                                                    @break
                                                @case('ar')
                                                    {{ $post->sous_categorie_info->categorie->title_ar ?: \App\Traits\TranslateTrait::TranslateText($post->sous_categorie_info->categorie->titre) }}
                                                    @break
                                                @default
                                                    {{ $post->sous_categorie_info->categorie->titre }}
                                            @endswitch
                                        </span>

                                    </span>
                                </a>
                                <span class="text-muted">
                                    &nbsp;
                                </span>
                                <a href="{{ route('shop') }}?id_categorie={{ $post->sous_categorie_info->categorie->id }}&selected_sous_categorie={{ $post->sous_categorie_info->id }}"
                                    class=" rounded px-2 py-1 mr-2 strong" style="background-color: #ecedf1">
                                    @switch(app()->getLocale())
                                        @case('en')
                                            {{ $post->sous_categorie_info->title_en ?: $post->sous_categorie_info->titre }}
                                            @break
                                        @case('ar')
                                            {{ $post->sous_categorie_info->title_ar ?: $post->sous_categorie_info->titre }}
                                            @break
                                        @default
                                            {{ $post->sous_categorie_info->titre }}
                                    @endswitch

                                </a>
                            </div>
                            <div class="prt_02 mb-5 mt-3">

                                <h2 class="post-title mb-1 mt-2 text-capitalize">
                                    {{ $post->titre }}
                                </h2>

                                <div class="text-left">
                                    <div class="elis_rty mt-2">
                                        @if ($post->changements_prix->count())
                                            <span class="strong fs-lg" style="color: ''; font-size: smaller;">
                                                <strike>
                                                    {{ $post->getOldPrix() }}
                                                </strike> <sup>{{ __('currency') }}</sup>
                                            </span>
                                            <br>
                                            <span class="h5 strong" style="color: #008080;">
                                                {{ $post->getPrix() }} <sup>{{ __('currency') }}</sup>
                                            </span>
                                        @else
                                            <span class="ft-bold color strong fs-lg" style="color: #008080;">
                                                {{ $post->getPrix() }} <sup>{{ __('currency') }}</sup>
                                            </span>
                                        @endif

                                        <span class="badge-frais-details">
                                            <img width="25" height="25"
                                                src="https://img.icons8.com/laces/25/018d8d/delivery.png" alt="delivery" />
                                            + {{ __('Frais de Livraison')}}
                                        </span>
                                    </div>
                                </div>
                            </div>


                            @if ($post->statut == 'vente')
                            @auth
                                @if ($post->id_user != Auth::id())
                                    <button type="button"
                                        class="btn btn-block @if ($produit_in_cart) bg-dark @else hover-black @endif mb-2 p-3"
                                        id="btn-add-to-card" onclick="add_cart({{ $post->id }})" data-retire="{{ __('Retire2') }}" data-ajouter="{{ __('Ajouter') }}">
                                        <i class="lni lni-shopping-basket mr-2"></i>
                                        <span id="add-cart-text-btn">
                                            {{ $produit_in_cart ? __("Retire2") : __("Ajouter") }}
                                        </span>
                                    </button>
                                @endif
                            @endauth

                            @guest
                                <button type="button" class="btn btn-block bg-dark mb-2 p-3 hover-black" data-toggle="modal"
                                    data-target="#login">
                                    <i class="lni lni-shopping-basket mr-2"></i>
                                    {{ __("Ajouter") }}
                                </button>
                            @endguest
                        @endif


                            @auth
                                @if (Auth::id() == $post->id_user && $post->statut !== 'vendu')
                                    <button type="button" onclick="Update_post_price({{ $post->id }})"
                                        class="btn btn-default btn-block mb-2" type="button">
                                        <i class="bi bi-pencil-square"></i>
                                        {{ __('Réduire le prix') }}
                                    </button>
                                @endif
                                @if (Auth::id() != $post->id_user && $post->statut !== 'vendu')
                                    <button
                                        class="btn btn-default btn-block btn-add-favoris @if ($isFavorited) btn-favoris-added @endif"
                                        type="button" @guest data-toggle="modal" data-target="#login" @endguest
                                        data-id="{{ $post->id }}"
                                        data-add-text="{{ __('Ajouter aux favoris') }}"
                                        data-remove-text="{{ __('Retirer des favoris') }}"
                                        >
                                        <i class="lni lni-heart mr-2"></i>
                                        <span class="text">
                                            {{ $isFavorited ? __('Retirer des favoris') : __('Ajouter aux favoris') }}

                                        </span>
                                    </button>
                                    <br>
                                    <div class="text-center">
                                        @if ($is_alredy_signaler)
                                            <span class=" text-danger cursor">
                                                <i class="bi bi-exclamation-octagon"></i>
                                                {{ __('Vous avez déjà signalé cette annonce !') }}
                                            </span>
                                        @else
                                            <span class=" text-danger cursor" data-toggle="modal" data-target="#signalModal_{{ $post->id }}">
                                                <i class="bi bi-exclamation-octagon"></i>
                                                {{ __('Signaler cette annonce') }}
                                            </span>
                                        @endif

                                        <br><br>
                                    </div>
                                @endif
                            @endauth
                            <hr>
                            <div class="prt_03 mb-4">
                                <b class="text-black h5">{{ __('Détails') }}</b>
                                <p>
                                <table>
                                    <tr>
                                        <td style="min-width: 130px" class="cell cell-bold">{{ __('Condition') }}</td>
                                        <td class="text-black cell">
                                             {{-- {{ \App\Traits\TranslateTrait::TranslateText($post->etat) }} --}}
                                             {{ __($post->etat) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell cell-bold">{{ __('Catégorie') }}</td>
                                        <td class="text-black cell">

                                            {{ \App\Traits\TranslateTrait::TranslateText($post->sous_categorie_info->categorie->titre ?? '') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell cell-bold">{{ __('Région') }}</td>
                                        <td class="text-black cell">
                                            {{ __($post->region->nom ?? '') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell cell-bold">{{ __('Publié le') }}</td>
                                        <td class="text-black cell">
                                            {{ Carbon\Carbon::parse($post->created_at)->format('d/m/Y') }} </td>
                                    </tr>
                                    @forelse ($post->proprietes ?? []  as $key => $value)
                                        <tr>
                                            <td class="cell cell-bold">
                                                {{ __(ucfirst($key)) }}
                                            </td>
                                            <td class="text-black cell">
                                                @if ($key == 'Couleur')
                                                    @if ($value == '#000000000')
                                                        <img src="/icons/color-wheel.png" height="20"
                                                            width="20" alt="multicolor"
                                                            title="Multi color" srcset="">
                                                    @else
                                                        <script>
                                                            getColorName('{{ $value }}');
                                                        </script>

                                                        <div style="display: flex; align-items: center;">
                                                            <div style="width: 24px; height: 24px; background-color: {{ $value }}; border-radius: 50%; border: 2px solid #ccc; margin-right: 8px;"></div>
                                                        </div>
                                                    @endif
                                                @else
                                                    <span class="text-capitalize">
                                                        {{ __($value) }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </table>
                                </p>
                                <b class="text-black h5">{{ __('Description') }}</b>
                                <p>
                                    @if ($post->description)
                                        {{$post->description}}
                                    @else
                                        <div class="text-muted text-center">
                                            <i>
                                                <i class="bi bi-info-circle color"></i>
                                                {{ __('Aucune description disponible !') }}
                                            </i>
                                        </div>
                                    @endif

                                </p>
                                <div class="shopiner-heading mobile-only">
                                    <hr>
                                    <h5 style="font-size: 20px;">
                                        <b>{!! __('title') !!}</b>
                                    </h5>
                                    <div style="margin-top:20px;">
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
                                                            {{ $user->username }}
                                                        </a>
                                                    </h4>
                                                    <div>
                                                        @php
                                                            $count = number_format($user->averageRating->average_rating ?? 1);
                                                            $avis = $user->getReviewsAttribute->count();
                                                        @endphp

                                                        <x-Etoiles :count="$count" :avis="$avis"></x-Etoiles>

                                                        <div>
                                                            <span>
                                                                <b> {{ $avis }} </b> {{ trans_choice('messages.avis', $avis, ['count' => $avis]) }}
                                                            </span>
                                                            |
                                                            <span>
                                                                <b>{{ $user->total_sales->count() }}</b>  {{ trans_choice('messages.sales', $salesCount) }}
                                                            </span>
                                                            |
                                                            <span>
                                                                <b>{{ $user->ValidatedPosts->count() }}</b> {{ trans_choice('messages.annonces', $adsCount) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ======================= Product Detail End ======================== -->



    <!-- ======================= Similar Products Start ============================ -->
    <section class="middle pt-0">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="sec_title position-relative ">
                        <h3 class="ft-bold pt-3">
                            {{ __('Articles similaires') }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="slide_items">
                        @foreach ($other_products as $other)
                            <!-- single Item -->
                            <div class="single_itesm">
                                <div class="product_grid card b-0 mb-0">
                                    <button type="button" class="badge badge-like-post-count btn-favorite-post position-absolute ab-right cusor {{ $other->isFavoritedByUser(Auth::id()) ? 'active' : '' }}"
                                        id="post-{{ $other->id }}" data-post-id="{{ $other->id }}"
                                        onclick="toggleFavorite({{ $other->id }})">
                                    <i class="bi bi-suit-heart-fill"></i>
                                    <span class="count">{{ $other->favoris->count() }}</span>
                                    </button>
                                    <div class="card-body p-0">
                                        <div class="shop_thumb position-relative">
                                            <a class="card-img-top d-block overflow-hidden"
                                                href="/post/{{ $other->id }}"><img class="card-img-top"
                                                    src="{{ Storage::url($other->photos[0] ?? '') }}" alt="...">
                                            </a>
                                        </div>
                                    </div>
                                    <x-SubCardPost :idPost="$other->id"></x-SubCardPost>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- ======================= Similar Products End ============================ -->

    <section class="middle pt-0">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="sec_title position-relative">
                        <h3 class="ft-bold pt-3">
                            {!! __('title2') !!} ({{ $user->username }})
                        </h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="slide_items">
                        @foreach ($user_products as $product)
                            <!-- single Item -->
                            <div class="single_itesm">
                                <div class="product_grid card b-0 mb-0">
                                    <button type="button" class="badge badge-like-post-count btn-favorite-post position-absolute ab-right cusor {{ $product->isFavoritedByUser(Auth::id()) ? 'active' : '' }}"
                                        id="post-{{ $product->id }}" data-post-id="{{ $product->id }}"
                                        onclick="toggleFavorite({{ $product->id }})">
                                    <i class="bi bi-suit-heart-fill"></i>
                                    <span class="count">{{ $product->favoris->count() }}</span>
                                    </button>
                                    <div class="card-body p-0">
                                        <div class="shop_thumb position-relative">
                                            <a class="card-img-top d-block overflow-hidden"
                                                href="/post/{{ $product->id }}"><img class="card-img-top"
                                                    src="{{ Storage::url($product->photos[0] ?? '') }}" alt="...">
                                            </a>
                                        </div>
                                    </div>
                                    <x-SubCardPost :idPost="$product->id"></x-SubCardPost>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </section>

    @auth
        @if (Auth::id() != $post->id_user)
            <!-- Signalement Modal -->
            <div class="modal fade" id="signalModal_{{ $post->id }}" tabindex="-1" role="dialog" aria-labelledby="signalModalLabel_{{ $post->id }}" aria-hidden="true">
                <div class="modal-dialog modal-xl login-pop-form" role="document">
                    <div class="modal-content">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="modal-body p-5">
                            @livewire('User.Signalement', ['post' => $post])
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal -->
        @endif
    @endauth


    <!-- Modal view-->
    <div class="modal fade" id="Modal-view" tabindex="1" role="dialog" aria-labelledby="loginmodal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg login-pop-form text-center" role="document">
            <div>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" id="btn-close">
                    <i class="bi bi-x-circle"></i>
                </button>
                <img src="" id="modal-view-image" alt="image" class="zoom-in modal-view-img">
            </div>
        </div>
    </div>
    <!-- End Modal -->

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
                            {!! __('Noter') !!}
                        </h4>
                    </div>
                    @livewire('User.Rating',['id_user'=>$user->id])
                </div>
            </div>
        </div>
    </div>

    @if (session('show_validation_modal'))
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(56, 54, 54, 0.5); display: flex; justify-content: center; align-items: center; z-index:9999;">
            <div style="background: white; padding: 2rem; border-radius: 10px; text-align: center; max-width: 400px; width: 100%;">
                <h2 style="font-size: 1.25rem; font-weight: bold;">{{ __('Votre publication est en cours de validation') }}</h2>
                <p style="margin-top: 0.5rem;">{{ __('Vous recevrez une notification une fois qu\'elle sera approuvée.') }}</p>
                <a href="{{ route('home') }}"
                style="margin-top: 1rem; display:inline-block; padding: 0.5rem 1rem; background: #008080; color: white; border: none; border-radius: 5px;">
                    {{ __('Fermer') }}
                </a>
            </div>
        </div>
    @endif

    <style>
        .sp-current-big {
            width: 100% !important;
        }
        .post-title {
            display: block;
            max-width: 100%;
            word-wrap: break-word;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .expanded-title {
            max-width: none;
            overflow: visible;
            text-overflow: clip;
        }
        /* Hide the second heading on larger screens */
        .mobile-only {
            display: none;
        }

        /* Show the second heading only on small screens */
        @media screen and (max-width: 768px) {
            .shopiner-heading {
                display: none;
            }
            .mobile-only {
                display: block;
            }
        }

    </style>
    <script>
        $(document).ready(function() {
            $("#imgPrincipale").on('click', function() {
                var url = $(this).attr("src");
                $('#modal-view-image').attr("src", url);
                $('#Modal-view').modal("show");
            });
            $('#btn-close').click(function() {
                $('#Modal-view').modal('hide');
            });
        });
    </script>

@endsection
