@extends('User.fixe')
@section('titre', 'Annonces Aimés')
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
                                Annonces Aimés
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
                            <td>Publication</td>
                            <td>Prix</td>
                            <td>Statut</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse (Auth::user()->likes as $like)
                            <tr id="tr-{{ $like->id }}">
                                <td>
                                    <div class="d-flex justify-content-start">
                                        <div class="avatar-post-like">
                                            <img src="{{ $like->post->FirstImage() }}" alt="" 
                                                srcset="">
                                        </div>
                                        <div class="my-auto">
                                           <div class="h5">
                                            {{ $like->post->titre }}
                                           </div>
                                            <span class="small">
                                                Publié le   {{ $like->post->created_at }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ $like->post->getPrix() }} DH
                                </td>
                                <td>
                                    {{ $like->post->statut }}
                                </td>
                                <td class="text-end">
                                    <span class="text-danger cusor" type="button" onclick="remove_liked({{ $like->id }})">
                                        <b>
                                            <i class="bi bi-heartbreak"></i> Rétirer
                                        </b>
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="alert text-center">
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
