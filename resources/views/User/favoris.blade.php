@extends('User.fixe')
@section('titre', 'Mes Favoris')
@section('content')
@section('body')
    <!-- ======================= Top Breadcrubms ======================== -->
    <div class="gray py-3">
        <div class="container">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/">Accueil</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Mes Favoris
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
                <table class="table">
                    <thead style="background-color: #008080;color: white !important;">
                        <tr>
                            <td>Annonces</td>
                            <td>Catégorie</td>
                            <td>Prix</td>
                            <td>Vendeur</td>
                            <td>Statut de l’article</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse (Auth::user()->favoris as $favoris)
                            <tr id="tr-{{ $favoris->id }}">
                                <td>
                                    <div class="d-flex justify-content-start">
                                        <div class="avatar-post-like">
                                            <img src="{{ $favoris->post->FirstImage() }}" alt="" srcset="">
                                        </div>
                                        <div class="my-auto">
                                            <a href="{{ route('details_post_single', ['id' => $favoris->post->id]) }}"
                                                class="h6">
                                                {{ $favoris->post->titre }}
                                            </a>
                                            <br>
                                            <span class="small">
                                                Publié le {{ $favoris->post->created_at }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">
                                        {{ $favoris->post->sous_categorie_info->categorie->titre }}
                                    </span>
                                </td>
                                <td class="strong">
                                    {{ $favoris->post->getPrix() }} DH
                                </td>
                                <td>
                                    <a href="{{ route('user_profile', ['id' => $favoris->post->user_info->id]) }}"
                                        class="strong">
                                        {{ $favoris->post->user_info->username }}
                                    </a>
                                </td>
                                <td class="strong">
                                    @if ($favoris->post->sell_at)
                                        <span class="text-danger">
                                            Indisponible
                                        </span>
                                    @else
                                        <span class="text-success">
                                            Disponible
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button class="text-danger btn btn-sm cusor" type="button"
                                        onclick="remove_favoris({{ $favoris->id }})">
                                        <b>
                                            <i class="bi bi-x"></i> Rétirer
                                        </b>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="alert alert-info text-center">
                                        <img width="100" height="100"
                                            src="https://img.icons8.com/ios/100/008080/favorites.png" alt="favorites" />
                                        <br>
                                        <i class="color">
                                            Vous n'avez pas encore de favoris !
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
