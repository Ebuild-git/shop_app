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
                        @foreach ($photos as $photo)
                            <div class="carousel-item active">
                                <img class="d-block w-100" src="{{ Storage::url($photo) }}" alt="First slide">
                            </div>
                        @endforeach
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
            </div>
            <div class="col-sm-6 position-relative">
                <div class="d-flex justify-content-start">
                    <div>
                        <img src=" {{ Storage::url($post->user_info->avatar) }} " class="rounded-circle">
                    </div>
                    <div>
                        <b>
                            <i class="bi bi-person-circle"></i>
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
                <hr>
                <button class="bg-red btn">
                    <i class="bi bi-bag"></i>
                    Commander cet article
                </button>

                <div class="fixed-bottom">ssssssssssssssssss</div>
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
@endsection
