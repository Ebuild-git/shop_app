@extends('User.fixe')
@section('titre', 'Créer une publication')
@section('content')
@section('body')

    <!-- ======================= Shop Style 1 ======================== -->
    <section class="bg-cover" style="background:url('/icons/post.jpg') no-repeat;">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="text-left py-5 mt-3 mb-3">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ======================= Shop Style 1 ======================== -->
    <!-- ======================= Top Breadcrubms ======================== -->
    <div class="gray py-3">
        <div class="container">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Vendre un article</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ======================= Top Breadcrubms ======================== -->
    <div class="container pt-5 pb-5">
        <div class="text-center">
            <h2 class="mb-2 ft-bold">Publier un article ?</h2>
        </div>
        <div>
            @livewire('User.CreatePost', ['id' => $id ?? ''])
        </div>

    </div>


@endsection
