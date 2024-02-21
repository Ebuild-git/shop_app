@extends('User.fixe')
@section('titre', $post->titre)
@section('content')
@section('body')

    @php
        $photos = json_decode($post->photos, true);
    @endphp
    <div class="container pt-5 pb-5">
        <div class="row">
            <div class="col-sm-6">
                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img class="d-block w-100" src="{{ Storage::url($photos[0]) }}" alt="First slide" id="big-view">
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
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
            <div class="col-sm-6">
                <div class="d-flex justify-content-start">
                    <div>
                        <img src=" {{ Storage::url($post->user_info->avatar) }} "
                            style="height: 50px;width: 50px;background-color: #ebeef1" class="rounded-circle">
                    </div>
                    <div>
                        <b>
                            Auteur :
                        </b> {{ $post->user_info->name }}
                        @if ($post->user_info->certifier == 'oui')
                            <img width="20" height="20"
                                src="https://img.icons8.com/sf-regular-filled/48/40C057/approval.png" alt="approval"
                                title="Certifié" />
                        @endif
                        <br>
                        <b>
                            Membre depuis : {{ $post->user_info->created_at->format('d/m/Y') }}
                        </b>
                    </div>
                </div>
                <br><br>
                <h5 class="text-red">
                    <b>
                        {{ $post->prix }}
                    </b>
                    DT
                </h5>
                <h5>
                    {{ $post->titre }}
                </h5>
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
                        
                            <button class="bg-red btn">
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
        </div>

        <div class="py-5">
            <h5>
                Autres articles de la meme categorie
            </h5>
            <br>
            <div class="row">
                @foreach ($other_product as $item)
                    @php
                        $photo = json_decode($item->photos, true);
                    @endphp
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <img class="img-fluid" alt="100%x280" src="{{ Storage::url($photo[0]) }}">
                            <div class="card-body">
                                <span class="text-red">
                                    <strong>{{ $item->prix }}</strong> Dt
                                </span>
                                <h6 class="card-title">
                                    <a href="/post/{{ $item->id }}" class="alert-link">
                                        {{ $item->titre }}
                                    </a>
                                </h6>
                                <p class="card-text small text-muted">
                                    <b>
                                        <i class="bi bi-geo-alt"></i>
                                    </b> : {{ $item->ville }}<br>
                                    <b>
                                        <i class="bi bi-grid-1x2"></i>
                                    </b> : {{ $item->categorie_info->titre }},
                                    {{ $item->created_at }}
                                </p>

                            </div>

                        </div>
                    </div>
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
    @endauth
@endsection
