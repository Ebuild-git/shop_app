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
                    <select class="form-control cusor sm" name="month">
                        <option value="">Mois</option> <!-- Placeholder option -->
                        <option value="01" {{ $month == '01' ? 'selected' : '' }}>Janvier</option>
                        <option value="02" {{ $month == '02' ? 'selected' : '' }}>Février</option>
                        <option value="03" {{ $month == '03' ? 'selected' : '' }}>Mars</option>
                        <option value="04" {{ $month == '04' ? 'selected' : '' }}>Avril</option>
                        <option value="05" {{ $month == '05' ? 'selected' : '' }}>Mai</option>
                        <option value="06" {{ $month == '06' ? 'selected' : '' }}>Juin</option>
                        <option value="07" {{ $month == '07' ? 'selected' : '' }}>Juillet</option>
                        <option value="08" {{ $month == '08' ? 'selected' : '' }}>Août</option>
                        <option value="09" {{ $month == '09' ? 'selected' : '' }}>Septembre</option>
                        <option value="10" {{ $month == '10' ? 'selected' : '' }}>Octobre</option>
                        <option value="11" {{ $month == '11' ? 'selected' : '' }}>Novembre</option>
                        <option value="12" {{ $month == '12' ? 'selected' : '' }}>Décembre</option>
                    </select>
                    <select class="form-control cusor sm" name="year" id="year-select">
                        <option value="">Année</option> <!-- Placeholder option -->
                    </select>
                    <script>
                        const yearSelect = document.getElementById('year-select');
                        const startYear = 2000;
                        const endYear = new Date().getFullYear();
                        for (let year = startYear; year <= endYear; year++) {
                            const option = document.createElement('option');
                            option.value = year;
                            option.textContent = year;
                            if (year == "{{ $year }}") {
                                option.selected = true;
                            }
                            yearSelect.appendChild(option);
                        }
                    </script>
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
<style>
    .filter-container {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 20px;
    }
    .filter-group {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .filter-select {
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    .filter-select:focus {
        outline: none;
        border-color: #00a699;
        box-shadow: 0 0 0 2px rgba(0,166,153,0.2);
    }
    .btn.bg-red {
        transition: all 0.3s ease;
    }
    .btn.bg-red:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
</style>

@endsection

@section('modal')
@endsection
