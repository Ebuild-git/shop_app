@extends('User.fixe')
@section('titre', 'Mes Favoris')
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
                            {{ __('my_liked')}}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<section class="middle">
    <div class="container">
        <form method="get" action="{{ route('favoris') }}" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
            <div class="row">
                <div class="col-sm-9 my-auto">
                    <b>
                        {{ __('results')}} :
                    </b>
                    {{ $favoris->count() }}
                </div>
                <div class="col-sm-3">
                    <div class="filter-container">
                        <div class="filter-group">
                            <select class="filter-select" name="month">
                                <option value="">{{ __('month') }}</option>
                                <option value="01">{{ __('january') }}</option>
                                <option value="02">{{ __('february') }}</option>
                                <option value="03">{{ __('march') }}</option>
                                <option value="04">{{ __('april') }}</option>
                                <option value="05">{{ __('may') }}</option>
                                <option value="06">{{ __('june') }}</option>
                                <option value="07">{{ __('july') }}</option>
                                <option value="08">{{ __('august') }}</option>
                                <option value="09">{{ __('september') }}</option>
                                <option value="10">{{ __('october') }}</option>
                                <option value="11">{{ __('november') }}</option>
                                <option value="12">{{ __('december') }}</option>
                            </select>
                            <select class="filter-select" name="year" id="year-select">
                                <option value="">{{ __('year') }}</option>
                            </select>
                            <button class="btn bg-red p-2" type="submit">
                                <i class="bi bi-filter"></i>
                                {{ __('filter') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div>
            <table class="table" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
                <thead style="background-color: #008080;color: white !important;">
                    <tr>
                        <th>{{ __('Annonces')}}</th>
                        <th>{{ __('categories') }}</th>
                        <th>{{ __('price') }}</th>
                        <th>{{ __('seller') }}</th>
                        <th>{{ __('article_status') }}</th>
                        <th>{{ __('added_on') }}</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($favoris as $favori)
                    <tr id="tr-{{ $favori->id }}">
                        <td>
                            <div class="d-flex justify-content-start">
                                <div class="avatar-post-like">
                                    <img src="{{ $favori->post->FirstImage() }}" alt="" srcset="">
                                </div>
                                <div class="my-auto">
                                    <a href="{{ route('details_post_single', ['id' => $favori->post->id]) }}"
                                        class="h6">

                                        {{ $favori->post->titre}}
                                    </a>
                                    <br>
                                    <span class="small">
                                        Publié le {{ $favori->post->created_at }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-muted">

                                {{ \App\Traits\TranslateTrait::TranslateText($favori->post->sous_categorie_info->categorie->titre) }}
                            </span>
                        </td>
                        <td class="strong">
                            @if ($favori->post->changements_prix->count())
                                <span style="color: grey; font-size: smaller;">
                                    <strike>
                                        {{ $favori->post->getOldPrix() }}
                                    </strike> <sup>{{ __('currency') }}</sup>
                                </span>
                                <br>
                                <span style="color: #008080;">
                                    {{ $favori->post->getPrix() }} <sup>{{ __('currency') }}</sup>
                                </span>
                            @else
                                <span style="color: #008080;">
                                    {{ $favori->post->getPrix() }} <sup>{{ __('currency') }}</sup>
                                </span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('user_profile', ['id' => $favori->post->user_info->id]) }}"
                                class="strong">
                                {{ $favori->post->user_info->username }}
                            </a>
                        </td>
                        <td class="strong">
                            @if ($favori->post->sell_at)
                            <span class="text-danger">

                                {{ \App\Traits\TranslateTrait::TranslateText($favori->post->statut) }}
                            </span>
                            @else
                            <span class="text-success">
                                {{ __('available')}}
                            </span>
                            @endif
                        </td>
                        <td>
                            {{ $favori->created_at->format('d-m-Y') }}
                        <td>
                        <td class="text-end">
                            <i class="bi bi-trash3 text-danger btn btn-sm cursor"
                                style="margin-top: auto;"
                                onclick="remove_favoris({{ $favori->id }})"></i>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="alert alert-info text-center">
                                <img width="100" height="100" src="https://img.icons8.com/ios/100/008080/favorites.png"
                                    alt="favorites" />
                                <br>
                                <i class="color">
                                    {{ __('no_favorites')}}
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

<style>
    .filter-container {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 20px;
        position: relative;
    }
    .filter-group {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .filter-select {
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        position: relative;
    }
    .filter-select:focus {
        outline: none;
        border-color: #00a699;
        box-shadow: 0 0 0 2px rgba(0,166,153,0.2);
    }
    .filter-select option {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        min-width: 100%;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .btn.bg-red {
        transition: all 0.3s ease;
    }
    .btn.bg-red:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const yearSelect = document.getElementById('year-select');
        const currentYear = new Date().getFullYear();
        for (let year = currentYear; year >= 2024; year--) {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            yearSelect.appendChild(option);
        }
    });
</script>

@endsection
