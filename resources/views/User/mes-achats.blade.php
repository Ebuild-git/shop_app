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
                            <li class="breadcrumb-item">
                                <a href="/">Accueil</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Mes Achats
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <br>
    <div class="container">
        <form method="get" action="{{ route('mes-achats') }}">
            <div class="d-flex justify-content-between mb-3">
                <div>

                </div>
                <div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control cusor sm" placeholder="Mois / Année"
                            onfocus="(this.type='month')" onblur="(this.type='text')" lang="fr" name="date"
                            value="{{ $date ? $date : null }}">
                        <button type="submit" class="btn p-2 bg-red  ">
                            Filtrer par date
                        </button>
                    </div>
                </div>
            </div>
        </form>
        @include('components.Liste-mes-achats',["achats"=>$achats])
    </div>


@endsection
