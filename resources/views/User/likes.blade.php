@extends('User.fixe')
@section('titre', 'Mes coups de coeur')
@section('content')
@section('body')
    <!-- ======================= Top Breadcrubms ======================== -->
    <div class="gray py-3" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
        <div class="container">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/"><i class="fas fa-home"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ __('my_favorites')}}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="middle">
        <div class="container">
            <div>
                <table class="table" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
                    <thead style="background-color: #008080;color: white !important;">
                        <tr>
                            <td>{{ __('Annonces')}}</td>
                            <td>{{ __('categories') }}</td>
                            <td>{{ __('price') }}</td>
                            <td>{{ __('seller') }}</td>
                            <td>{{ __('popularity') }}</td>
                            <td>{{ __('status') }}</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse (Auth::user()->likes as $like)
                            @if ($like->post)
                                <tr id="tr-{{ $like->id }}">
                                    <td>
                                        <div class="d-flex justify-content-start">
                                            <div class="avatar-post-like">
                                                <img src="{{ $like->post->FirstImage() }}" alt="" srcset="">
                                            </div>
                                            <div class="my-auto">
                                                <a href="{{ route('details_post_single', ['id' => $like->post->id]) }}"
                                                    class="h6">

                                                    {{ \App\Traits\TranslateTrait::TranslateText($like->post->titre) }}
                                                </a>
                                                <br>
                                                <span class="small">
                                                    {{ __('Publié le')}} {{ $like->post->created_at }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">

                                            {{ \App\Traits\TranslateTrait::TranslateText($like->post->sous_categorie_info->categorie->titre) }}
                                        </span>
                                    </td>
                                    <td class="strong">
                                        @if ($like->post->changements_prix->count())
                                            <span class=" color">
                                                <strike>
                                                    {{ $like->post->getOldPrix() }}
                                                </strike> <sup>{{ __('currency') }}</sup>
                                            </span>
                                            <br>
                                            <span class="text-danger  ">
                                                {{ $like->post->getPrix() }} <sup>{{ __('currency') }}</sup>
                                            </span>
                                        @else
                                            <span class="ft-bold color ">
                                                {{ $like->post->getPrix() }} <sup>{{ __('currency') }}</sup>
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('user_profile', ['id' => $like->post->user_info->id]) }}"
                                            class="strong">
                                            {{ $like->post->user_info->username }}
                                        </a>
                                    </td>

                                    <td>
                                        {{ $like->post->getLike->count() }}
                                        {{ $like->post->getLike->count() > 1 ? __('like_plural') : __('like_singular') }}
                                        <i class="bi bi-heart-fill text-danger"></i>
                                    </td>

                                    <td class="strong">
                                        <span class="{{ $like->post->sell_at ? 'text-danger' : 'text-success' }}">
                                            {{ $like->post->sell_at ? __('unavailable') : __('available') }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <button button class="text-danger btn btn-sm cusor" type="button"
                                            onclick="remove_liked({{ $like->id }})">
                                            <b>
                                                <i class="bi bi-heartbreak"></i> {{ __('remove') }}
                                            </b>
                                        </button>
                                    </td>
                                </tr>
                            @else
                                @php
                                    //delete
                                    $like->delete();
                                @endphp
                            @endif
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="alert alert-info text-center">
                                        <img width="100" height="100"
                                            src="https://img.icons8.com/ios/100/008080/filled-like.png" alt="filled-like" />
                                        <br>
                                        <i class="color">
                                            {{ __('no_favorites_yet') }}
                                        </i>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </section>
@endsection
