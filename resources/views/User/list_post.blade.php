@extends('User.fixe')
@section('titre', 'Mes ' . $type . 's')
@section('body')

<!-- ======================= Top Breadcrubms ======================== -->
<div class="gray py-3" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
    <div class="container">
        <div class="row">
            <div class="colxl-12 col-lg-12 col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/" aria-label="{{ __('home') }}"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">

                            {{ \App\Traits\TranslateTrait::TranslateText('Mes ' . $type . 's') }}

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
            <b>
                @if($type == 'vente')

                    {{ \App\Traits\TranslateTrait::TranslateText('Nombre total de mes ventes :') }}
                @else

                    {{ \App\Traits\TranslateTrait::TranslateText('Nombre total de mes annonces :') }}
                @endif
            </b> {{ $posts->count() }}
        </div>
        <div class="col-sm-8" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
            <form method="get" action="{{ route('mes-publication') }}">
                <div class="filter-container">
                    <div class="filter-group">
                        <input type="hidden" name="type" value="{{ $type }}">

                        <!-- Icon inside the input field as a placeholder -->
                        <input type="text" name="key" class="filter-select" value="{{ $key }}" placeholder="&#xF002; {{ __('keyword') }}" style="font-family:Arial, FontAwesome; min-width: 180px;">

                        <select class="filter-select" name="statut">
                            <option value="">{{ __('status') }}</option>
                            <option value="validation">{{ __('validation') }}</option>
                            <option value="vente">{{ __('vente') }}</option>
                            <option value="vendu">{{ __('vendu') }}</option>
                            <option value="livraison">{{ __('livraison') }}</option>
                            <option value="livré">{{ __('livré') }}</option>
                            <option value="refusé">{{ __('refusé') }}</option>
                            <option value="préparation">{{ __('préparation') }}</option>
                            <option value="en voyage">{{ __('en voyage') }}</option>
                            <option value="en cours de livraison">{{ __('en cours de livraison') }}</option>
                            <option value="ramassée">{{ __('ramassée') }}</option>
                            <option value="retourné">{{ __('retourné') }}</option>
                        </select>


                        <select class="filter-select" name="month">
                            <option value="">{{ __('month') }}</option>
                            <option value="01" {{ $month == '01' ? 'selected' : '' }}>{{ __('january') }}</option>
                            <option value="02" {{ $month == '02' ? 'selected' : '' }}>{{ __('february') }}</option>
                            <option value="03" {{ $month == '03' ? 'selected' : '' }}>{{ __('march') }}</option>
                            <option value="04" {{ $month == '04' ? 'selected' : '' }}>{{ __('april') }}</option>
                            <option value="05" {{ $month == '05' ? 'selected' : '' }}>{{ __('may') }}</option>
                            <option value="06" {{ $month == '06' ? 'selected' : '' }}>{{ __('june') }}</option>
                            <option value="07" {{ $month == '07' ? 'selected' : '' }}>{{ __('july') }}</option>
                            <option value="08" {{ $month == '08' ? 'selected' : '' }}>{{ __('august') }}</option>
                            <option value="09" {{ $month == '09' ? 'selected' : '' }}>{{ __('september') }}</option>
                            <option value="10" {{ $month == '10' ? 'selected' : '' }}>{{ __('october') }}</option>
                            <option value="11" {{ $month == '11' ? 'selected' : '' }}>{{ __('november') }}</option>
                            <option value="12" {{ $month == '12' ? 'selected' : '' }}>{{ __('december') }}</option>
                        </select>

                        <select class="filter-select" name="year" id="year-select">
                            <option value="">{{ __('year') }}</option>
                        </select>

                        <script>
                            const yearSelect = document.getElementById('year-select');
                            const startYear = 2024;
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

                        <button class="btn bg-red p-2" type="submit">
                            <i class="bi bi-filter"></i> {{ __('filter') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @include('components.Liste-mes-posts', ['posts' => $posts, 'showRemainingTimeColumn' => $type == 'vente'])

    </div>

</div>
<style>
    .filter-container {
        display: flex;
        justify-content: flex-end;

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
        background-color: #008080;
        color: white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border-radius: 4px;
    }
    .btn.bg-red:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }



/* Responsive Styles for Mobile */
@media (max-width: 768px) {
    .filter-container {
        justify-content: center;
        margin-bottom: 10px;
    }

    .filter-group {
        flex-direction: column;
        gap: 8px;
    }

    .filter-select, .btn.bg-red {
        width: 100%;
        font-size: 12px;
    }

    #table-scroll {
        height: auto; /* Adjust height to auto for better fit */
        max-height: 300px; /* Limit the max height on mobile to avoid excessive scrolling */
        overflow-x: auto; /* Ensure horizontal scrolling */
    }

    #table-wrapper table {
        min-width: 600px; /* Ensure the table is scrollable horizontally */
    }

    thead th, tbody td {
        font-size: 13px; /* Reduce font size for header and cells */
        padding: 6px; /* Reduce padding for better spacing */
    }

    tbody td {
        text-align: left;
    }
}

/* Further adjustments for very small screens */
@media (max-width: 480px) {
    .filter-group {
        gap: 6px;
    }

    thead th, tbody td {
        font-size: 10px; /* Further reduce font size */
        padding: 4px; /* Further reduce padding */
    }

    .filter-select, .btn.bg-red {
        padding: 5px 8px; /* Smaller padding for buttons and selects */
        font-size: 10px; /* Smaller font size */
    }

    #table-scroll {
        max-height: 250px; /* Further reduce max height for very small screens */
    }

    #table-wrapper table {
        min-width: 500px; /* Slightly reduce the min-width for smaller screens */
    }
}
</style>

@endsection

@section('modal')
@endsection
