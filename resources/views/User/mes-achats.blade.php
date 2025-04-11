@extends('User.fixe')
@section('titre', 'Créer une publication')
@section('content')
@section('body')

<!-- ======================= Top Breadcrumbs ======================== -->
<div class="gray py-3" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
    <div class="container">
        <div class="row">
            <div class="colxl-12 col-lg-12 col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/"><i class="fas fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('my_purchases')}}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<br>
<div class="container">
    <form method="get" action="{{ route('mes-achats') }}" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
        <div class="filter-container">
            <div class="filter-group">
                <select class="filter-select" name="month">
                    <option value="">{{ __('month') }}</option>
                    <option value="01">{{ __('january') }}</option>
                    <option value="02">{{ __('february') }}</option>
                    <option value="03">{{ __('march') }}</option>
                    <option value="04">{{ __('april') }}</option>
                    <option value="05">{{ __('may') }}</option>
                    <option value="06">{{ __('june') }}</option>
                    <option value="07">{{ __('july') }}</option>
                    <option value="08">{{ __('august') }}</option>
                    <option value="09">{{ __('september') }}</option>
                    <option value="10">{{ __('october') }}</option>
                    <option value="11">{{ __('november') }}</option>
                    <option value="12">{{ __('december') }}</option>
                </select>
                <select class="filter-select" name="year" id="year-select">
                    <option value="">{{ __('year') }}</option>
                </select>
                <button class="btn bg-red p-2" type="submit">
                    <i class="bi bi-filter"></i>
                    {{ __('filter') }}
                </button>
            </div>
        </div>
    </form>
    @include('components.Liste-mes-achats',["achats"=>$achats])
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const yearSelect = document.getElementById('year-select');
        const currentYear = new Date().getFullYear();
        for (let year = currentYear; year >= 2024; year--) {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            yearSelect.appendChild(option);
        }
    });
</script>

@endsection
