@extends('User.fixe')
@section('titre', 'Favoris')
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
                            <li class="breadcrumb-item active" aria-current="page">Favoris</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>  
    </div>

    <section class="middle">
        <div class="container">
            @livewire('User.ListFavoris')
        </div>
    </section>

@endsection
