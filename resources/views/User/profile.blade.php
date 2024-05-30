@extends('User.fixe')
@section('titre', $user->username)
@section('content')
@section('body')

    <style>
        .card-ps {
            border: solid 1px #00808065;
            border-radius: 5px;
            display: inline-block;
        }

        .text-end {
            text-align: right !important;
        }
    </style>
    <!-- ======================= Filter Wrap Style 1 ======================== -->
    <section class="py-3 ">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/">Accueil</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"> {{ $user->username }} </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- ============================= Filter Wrap ============================== -->


    <div class="container pb-3 pt-3">
        <div class="row">
            <div class="col-sm-4">
                <div>
                    <table>
                        <tr>
                            <td>
                                <div class="avatar-shopinner-details">
                                    <img src="{{ $user->getAvatar() }}" alt="avatar" height="80" srcset="">
                                </div>
                            </td>
                            <td>
                                <h4 class="h6">
                                    <a href="/user/{{ $user->id }}" class="h4">
                                        {{ $user->username }}
                                    </a>
                                </h4>
                                <div>
                                    @php
                                        $count = number_format($user->averageRating->average_rating ?? 1);
                                        $avis = $user->getReviewsAttribute->count();
                                    @endphp
                                    @livewire('User.Rating', ['user' => $user])
                                    <div>
                                        <span>
                                            <b>{{ $user->total_sales->count() }}</b> Ventes
                                        </span>
                                        |
                                        <span>
                                            <b>{{ $user->GetPosts->count() }}</b> Annonces
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <br>
                <div>
                    <p>
                        <i class="bi bi-calendar-check"></i> Membre dépuis les {{ $user->created_at }}
                        <br>
                        <i class="bi bi-envelope"></i> Email vérifié <b>{{ $user->photo_verified_at ? 'Oui' : 'Non' }}
                        </b>
                    </p>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="row">
                    @forelse ($posts as $post)
                        <div class="col-xl-4 col-sm-4 col-lg-4 col-md-6 col-6">
                            <div class="product_grid card b-0">
                                @if ($post->sell_at)
                                    <div class="badge badge-danger position-absolute ab-left text-upper">
                                        Vendu !
                                    </div>
                                @endif
                                <div class="badge badge-like-post-count position-absolute ab-right text-upper">
                                    <i class="far fa-heart"></i>
                                    {{ $post->getLike->count() }}
                                </div>
                                <div class="card-body p-0">
                                    <div class="shop_thumb position-relative">
                                        <a class="card-img-top d-block overflow-hidden"
                                            href="/post/{{ $post->id }}"><img class="card-img-top"
                                                src="{{ Storage::url($post->photos[0] ?? '') }}" alt="...">
                                        </a>
                                    </div>
                                </div>
                                <x-SubCardPost :idPost="$post->id"></x-SubCardPost>
                            </div>
                        </div>
                    @empty
                        <div class="col-sm-4 mx-auto text-center pt-5 pb-5">
                            <img width="80" height="80" src="/icons/web-design.png" alt="web-design" />
                            <div class="color col-lg-12" role="alert">
                                <b> Aucun article trouvé !</b>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
