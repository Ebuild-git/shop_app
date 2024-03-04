@extends('User.fixe')
@section('titre', $post->titre)
@section('content')
@section('body')

    @php
        $photos = json_decode($post->photos, true);
    @endphp
    <div class="container-fluid  pt-2 mt-4">
        <div class="row">
            <div class="col-sm-5">
                <div class="carouselExampleControls">
                    <img src="{{ Storage::url($photos[0] ?? '') }}" id="big-view" style="max-width: 100%">
                </div>
                <div class="p-2 d-flex justify-content-center">
                    @foreach ($photos as $photo)
                        <div>
                            <img class="m-1 mini-image-details" src="{{ Storage::url($photo) }}"
                                onclick="view('{{ Storage::url($photo) }}')">
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-sm-4">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="color-orange">
                            <b>
                                {{ $post->prix }}
                            </b>
                            DH
                        </h5>
                    </div>
                    <div>
                        <span class="text-muted ">
                            <i>{{ $post->etat }}</i>
                            <i class="bi bi-info-circle"></i>
                        </span>
                    </div>
                </div>
                <div class="h3">
                    {{ $post->titre }}
                </div>
                <div class="d-flex justify-content-between small">
                    <div>
                        publier le {{ date('d/m/Y', strtotime($post->created_at)) }}
                    </div>
                </div>
                <hr>
                <span class="h6">Localisation du produit</span>
                <div class="small">
                    <i class="bi bi-geo-alt"></i>
                    Ville : {{ $post->ville }} <br>
                    <i class="bi bi-grid-1x2"></i>
                    Catégorie : {{ $post->categorie_info->titre }} <br>
                    <i class="bi bi-grid-1x2"></i>
                    Sous-Catégorie : {{ $post->sous_categorie_info->titre }} <br>
                    <i class="bi bi-geo-alt"></i>
                    Gouvernorat :{{ $post->gouvernorat }}
                </div>
                <hr>
                <p>
                    <span class="h6">Description</span>
                    <br>
                    {{ $post->description }}
                </p>
            </div>
            <div class="col-sm-3">
                <div class="card p-2 mb-2 pt-4">
                    <h5 class="color-orange">
                        <b>
                            {{ $post->prix }}
                        </b>
                        <sup class="small">DT</sup>
                    </h5>
                    <div class="small">
                        <div class="mt-2">
                            <i class="bi bi-geo-alt"></i> votre adresse de livraison est :
                            @auth
                                <a href="/informations">
                                    <span class="color-orange"> {{ Auth::user()->adress }}</span>
                                </a>
                            @else
                                <a href="/connexion">
                                    <span class="color-orange">
                                        <i class="bi bi-person-circle"></i>
                                        Veuillez vous connecter
                                    </span>
                                </a>
                            @endauth

                        </div>
                    </div>
                    <br>
                    <table class="w-100 small">
                        <tr>
                            <td>Envoi</td>
                            <td class="value-td-detail"></td>
                        </tr>
                        <tr>
                            <td>Expédié par :</td>
                            <td class="value-td-detail">Shopin</td>
                        </tr>
                        <tr>
                            <td>Vendu par</td>
                            <td class="value-td-detail">{{ $post->user_info->name }}</td>
                        </tr>
                        <tr>
                            <td>Paiement a la livraison</td>
                            <td class="value-td-detail"> Oui</td>
                        </tr>
                        <tr>
                            <td>Nombre de proposition :</td>
                            <td class="value-td-detail">{{ $post->propositions->count() }}</td>
                        </tr>
                    </table>
                    @auth
                        <br>
                        @if ($post->id_user != Auth::user()->id)
                            @if ($post->sell_at == null)
                                <button class="bg-red btn " data-toggle="modal" data-target="#Modalcommander">
                                    Acheter
                                </button>
                            @else
                                <span class="text-success">
                                    <b>
                                        Vendu le {{ date('d/m/Y  h:m', strtotime($post->sell_at)) }}
                                        <i class="bi bi-check2-square"></i> .
                                    </b>
                                </span>
                            @endif
                        @endif
                    @endauth
                    @auth
                        <button class="btn btn-outline-danger mt-1" data-toggle="modal" data-target="#ModalSignalement">
                            <i class="bi bi-exclamation-triangle"></i>
                            Signaler cette annonce
                        </button>
                    @endauth
                </div>
                <div class="card p-2">
                    <div class="d-flex justify-content-start my-auto ">
                        <img src=" {{ Storage::url($post->user_info->avatar) }} "
                            style="height: 40px;width: 40px;background-color: #ebeef1" class="rounded-circle mr-3">

                        <div>
                            <span class="h6">
                                Auteur :
                            </span> {{ $post->user_info->name }}
                            @if ($post->user_info->certifier == 'oui')
                                <img width="15" height="15"
                                    src="https://img.icons8.com/sf-regular-filled/48/40C057/approval.png" alt="approval"
                                    title="Certifié" />
                            @endif
                            <a href="/user/{{ $post->user_info->id }}">
                                <span class="color-orange small">
                                    Voir le profil
                                </span>
                            </a>
                            <br>
                            <b class="small">
                                Membre depuis : {{ $post->user_info->created_at->format('d/m/Y') }}
                            </b>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-5">
            <h5>
                Autres articles de la meme categorie
            </h5>
            <br>
            <div class="row">
                @foreach ($other_product as $item)
                    <x-CardPost :post="$item" :class="'col-12 col-md-2 col-lg-4 col-xl-2'"></x-CardPost>
                @endforeach
            </div>
        </div>

    </div>





    <script>
        function view(url) {
            const img = document.getElementById('big-view');
            img.src = url;
        }
    </script>


    @auth
        <!-- Modal de signalement -->
        <div class="modal fade" id="ModalSignalement" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header bg-red rounded-0">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <i class="bi bi-exclamation-triangle"></i>
                            Signalement
                        </h5>
                        <hr>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @livewire('User.Signalement', ['post' => $post])
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de faire une proposition-->
        <div class="modal fade" id="Modalcommander" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header bg-red rounded-0">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <i class="bi bi-bag-heart"></i>
                            commander cet article
                        </h5>
                        <hr>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @livewire('User.MakeProposition', ['post' => $post])
                    </div>
                </div>
            </div>
        </div>
    @endauth
@endsection
