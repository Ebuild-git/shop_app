@extends('User.fixe')
@section('titre', 'Mes ' . $type . 's')
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
                            Mes {{ $type }}s
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>


<div class="container">
    <div class="p-3 row">
        <div class="col-sm-4 my-auto">
            <b>Nombre total de mes annonces :</b> {{ $posts->count() }}
        </div>
        <div class="col-sm-8">
            <form method="get" action="{{ route('mes-publication') }}">
                <div class="input-group mb-3">
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="text" name="key" class="form-control  sm" value="{{ $key }}" placeholder="Mot clé">
                    <select class="form-control sm  cusor" name="statut">
                        <option value="">Tous les statuts</option>
                        <option value="validation">En validation</option>
                        <option value="vente">En cour de vente</option>
                        <option value="vendu">Vendu</option>
                        <option value="livraison">En cour de Livraison</option>
                        <option value="livré">Déja livré</option>
                    </select>
                    <input type="text" class="form-control cusor sm" placeholder="Mois / Année"
                        onfocus="(this.type='month')" onblur="(this.type='text')" lang="fr" name="date"
                        value="{{ $date ? $date : null }}">
                    <div class="input-group-append">
                        <button class="btn bg-red p-2" type="submit">
                            <i class="bi bi-binoculars"></i>
                            Filtrer
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @include('components.Liste-mes-posts', ['posts' => $posts])
       
    </div>

</div>
@endsection

@section('modal')
<style>
    .data-input {
        overflow: hidden;
        width: 5px;
        height: 5px;
    }

    #table-wrapper {
        position: relative;
    }

    #table-scroll {
        height: 450px;
        overflow: auto;
        margin-top: 20px;
    }

    #table-wrapper table {
        width: 100%;

    }

    #table-wrapper table thead th .text {
        position: absolute;
        top: -20px;
        z-index: 2;
        height: 20px;
        width: 35%;
        border: 1px solid red;
    }
</style>


@endsection