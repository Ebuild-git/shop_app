@extends('User.fixe')
@section('titre', 'Créer une publication')
@section('content')
@section('body')

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
