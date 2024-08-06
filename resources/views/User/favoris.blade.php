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
        <form method="get" action="{{ route('favoris') }}">
            <div class="row">
                <div class="col-sm-9 my-auto">
                    <b>
                        Résultats :
                    </b>
                    {{ $favoris->count() }}
                </div>
                <div class="col-sm-3">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control cusor sm" placeholder="Mois / Année"
                            onfocus="(this.type='month')" onblur="(this.type='text')" lang="fr" name="date"
                            value="{{ $date ? $date : null }}">
                        <div class="input-group-append">
                            <button class="btn bg-red p-2" type="submit">
                                <i class="bi bi-binoculars"></i>
                                Filtrer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div>
            <table class="table">
                <thead style="background-color: #008080;color: white !important;">
                    <tr>
                        <th>Annonces</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Vendeur</th>
                        <th>Statut de l’article</th>
                        <th>Ajouté le</th>
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
                                        {{ $favori->post->titre }}
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
                                {{ $favori->post->sous_categorie_info->categorie->titre }}
                            </span>
                        </td>
                        <td class="strong">
                            @if ($favori->post->changements_prix->count())
                            <span class=" color">
                                <strike>
                                    {{ $favori->post->getOldPrix() }}
                                </strike> <sup>DH</sup>
                            </span>
                            <br>
                            <span class="text-danger  ">
                                {{ $favori->post->getPrix() }} <sup>DH</sup>
                            </span>
                            @else
                            <span class="ft-bold color ">
                                {{ $favori->post->getPrix() }} <sup>DH</sup>
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
                                Indisponible
                            </span>
                            @else
                            <span class="text-success">
                                Disponible
                            </span>
                            @endif
                        </td>
                        <td>
                            {{ $favori->created_at->format('d-m-Y') }}
                        <td>
                        <td class="text-end">
                            <button class="text-danger btn btn-sm cusor" type="button"
                                onclick="remove_favoris({{ $favori->id }})">
                                <b>
                                    <i class="bi bi-x"></i> Rétirer
                                </b>
                            </button>
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
