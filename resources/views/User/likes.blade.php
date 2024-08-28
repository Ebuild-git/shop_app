@extends('User.fixe')
@section('titre', 'Mes coups de coeur')
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
                                <a href="/"><i class="fas fa-home"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Mes coups de coeur
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
                            <td>Catégories</td>
                            <td>Prix</td>
                            <td>Vendeur</td>
                            <td>Popularité</td>
                            <td>Statut</td>
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
                                                    {{ $like->post->titre }}
                                                </a>
                                                <br>
                                                <span class="small">
                                                    Publié le {{ $like->post->created_at }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">
                                            {{ $like->post->sous_categorie_info->categorie->titre }}
                                        </span>
                                    </td>
                                    <td class="strong">
                                        @if ($like->post->changements_prix->count())
                                            <span class=" color">
                                                <strike>
                                                    {{ $like->post->getOldPrix() }}
                                                </strike> <sup>DH</sup>
                                            </span>
                                            <br>
                                            <span class="text-danger  ">
                                                {{ $like->post->getPrix() }} <sup>DH</sup>
                                            </span>
                                        @else
                                            <span class="ft-bold color ">
                                                {{ $like->post->getPrix() }} <sup>DH</sup>
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
                                        @if ($like->post->getLike->count() > 1)
                                            {{ $like->post->getLike->count() }} j'aimes
                                        @else
                                            {{ $like->post->getLike->count() }} j'aime
                                        @endif
                                        <i class="bi bi-heart-fill text-danger"></i>
                                    </td>
                                    <td class="strong">
                                        @if ($like->post->sell_at)
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
                                        <button button class="text-danger btn btn-sm cusor" type="button"
                                            onclick="remove_liked({{ $like->id }})">
                                            <b>
                                                <i class="bi bi-heartbreak"></i> Rétirer
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
                                            Vous n'avez pas encore aimé d'annonce !
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
