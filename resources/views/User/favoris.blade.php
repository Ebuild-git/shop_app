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
                    <div class="filter-container">
                        <div class="filter-group">
                            <select class="filter-select" name="month">
                                <option value="">Mois</option>
                                <option value="01">Janvier</option>
                                <option value="02">Février</option>
                                <option value="03">Mars</option>
                                <option value="04">Avril</option>
                                <option value="05">Mai</option>
                                <option value="06">Juin</option>
                                <option value="07">Juillet</option>
                                <option value="08">Août</option>
                                <option value="09">Septembre</option>
                                <option value="10">Octobre</option>
                                <option value="11">Novembre</option>
                                <option value="12">Décembre</option>
                            </select>
                            <select class="filter-select" name="year" id="year-select">
                                <option value="">Année</option>
                            </select>
                            <button class="btn bg-red p-2" type="submit">
                                <i class="bi bi-filter"></i>
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
                                <span style="color: grey; font-size: smaller;">
                                    <strike>
                                        {{ $favori->post->getOldPrix() }}
                                    </strike> <sup>DH</sup>
                                </span>
                                <br>
                                <span style="color: #008080;">
                                    {{ $favori->post->getPrix() }} <sup>DH</sup>
                                </span>
                            @else
                                <span style="color: #008080;">
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

<style>
    .filter-container {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 20px;
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
    }
    .filter-select:focus {
        outline: none;
        border-color: #00a699;
        box-shadow: 0 0 0 2px rgba(0,166,153,0.2);
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
