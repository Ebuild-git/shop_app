@extends('User.fixe')
@section('titre', $post->titre)
@section('content')
@section('body')

    @php
        $photos = json_decode($post->photos, true);
    @endphp
    <div class="container-fluid pt-5 pb-5">
        <div class="row">
            <div class="col-sm-4">
                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img class="d-block w-100" src="{{ Storage::url($photos[1] ?? '') }}" alt="First slide"
                                id="big-view">
                        </div>
                    </div>
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
            <div class="col-sm-5">

                <h5 class="text-success">
                    <b>
                        {{ $post->prix }}
                    </b>
                    DT
                </h5>
                <h5>
                    {{ $post->titre }}
                </h5>
                <b>
                   <i> Nombre de proposition : {{ $post->propositions->count() }}</i>
                </b>
                <hr>
                <div class="row">
                    <div class="col-sm-6 ">
                        <b>
                            <i class="bi bi-geo-alt"></i>
                            Ville :
                        </b> {{ $post->ville }} <br>
                        <b>
                            <i class="bi bi-grid-1x2"></i>
                            Catégorie :
                        </b> {{ $post->categorie_info->titre }} <br>
                    </div>
                    <div class="col-sm-6">
                        <b>
                            <i class="bi bi-geo-alt"></i>
                            Gouvernorat :
                        </b> {{ $post->gouvernorat }} <br>
                        <b>
                            <i class="bi bi-calendar3"></i>
                            Date de publication :
                        </b> {{ date('d/m/Y', strtotime($post->created_at)) }}
                    </div>
                </div>
                <br>
                <p>
                    {{ $post->description }}
                </p>
                <br>
                <div class="d-flex justify-content-between">
                    @auth
                        @if ($post->id_user != Auth::user()->id)
                            <button class="bg-red btn" data-toggle="modal" data-target="#Modalcommander">
                                <i class="bi bi-bag"></i>
                                Commander cet article
                            </button>

                            <button class="btn btn-warning" data-toggle="modal" data-target="#ModalSignalement">
                                <i class="bi bi-exclamation-triangle"></i>
                                Signaler cette annonce
                            </button>
                        @endif
                    @endauth
                </div>
                <div>

                </div>
            </div>
            <div class="col-sm-3">
                <div class="d-flex justify-content-start my-auto border p-3 rounded">
                    <img src=" {{ Storage::url($post->user_info->avatar) }} "
                        style="height: 40px;width: 40px;background-color: #ebeef1" class="rounded-circle mr-3">

                    <div>
                        <b>
                            Auteur :
                        </b> {{ $post->user_info->name }}
                        @if ($post->user_info->certifier == 'oui')
                            <img width="15" height="15"
                                src="https://img.icons8.com/sf-regular-filled/48/40C057/approval.png" alt="approval"
                                title="Certifié" />
                        @endif
                        <br>
                        <b class="small">
                            Membre depuis : {{ $post->user_info->created_at->format('d/m/Y') }}
                        </b>
                    </div>
                </div>
                <a href="/user/{{ $post->user_info->id }}" class="d-grid gap-2">
                    <button class="back-btn-red btn shadow-none d-block">
                        <i class="bi bi-eye"></i>
                        Voir le profil
                    </button>
                </a>
            </div>
        </div>

        <div class="py-5">
            <h5>
                Autres articles de la meme categorie
            </h5>
            <br>
            <div class="row">
                @foreach ($other_product as $item)
                    <x-CardPost :post="$item" :col=2></x-CardPost>
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
