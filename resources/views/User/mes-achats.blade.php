@extends('User.fixe')
@section('titre', 'Mes achats')
@section('body')

<style>
    .sales-page {
        background: #f5f6f8;
        min-height: 100vh;
        padding: 32px;
        font-family: 'DM Sans', sans-serif;
    }

    /* ── Header ── */
    .sales-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 28px;
        flex-wrap: wrap;
    }
    .sales-header h1 {
        font-size: 26px;
        font-weight: 700;
        flex: 1;
        color: #1a1a2e;
        margin: 0;
    }
    .filter-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .filter-bar select {
        font-family: 'DM Sans', sans-serif;
        font-size: 14px;
        border: 1.5px solid #e0e3ea;
        border-radius: 10px;
        background: #fff;
        color: #555;
        padding: 10px 14px;
        outline: none;
        cursor: pointer;
        box-shadow: none;
        transition: border-color .2s;
        padding-right: 34px;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23888' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
    }
    .filter-bar select:focus {
        border-color: #0d7c7c;
    }
    .btn-filter-submit {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #0d7c7c;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-family: 'DM Sans', sans-serif;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        transition: background .2s, transform .15s;
    }
    .btn-filter-submit:hover {
        background: #0a6060;
        transform: translateY(-1px);
    }

    /* ── Stat Cards ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }
    .stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 20px 22px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 4px rgba(0,0,0,.05);
    }
    .stat-icon {
        width: 54px; height: 54px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .stat-icon.teal   { background: #0d7c7c; }
    .stat-icon.blue   { background: #3f7fe0; }
    .stat-icon.green  { background: #27ae60; }
    .stat-icon svg { width: 24px; height: 24px; color: #fff; }
    .stat-info p { font-size: 13px; color: #888; margin-bottom: 4px; }
    .stat-info strong { font-size: 26px; font-weight: 700; color: #1a1a2e; }

    @media (max-width: 1100px) { .stats-grid { grid-template-columns: repeat(2,1fr); } }
    @media (max-width: 700px) {
        .sales-page { padding: 16px; }
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .sales-header { flex-direction: column; align-items: flex-start; }
        .filter-bar { width: 100%; }
        .filter-bar select,
        .btn-filter-submit { width: 100%; }
    }
</style>

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>

<div class="sales-page" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">

    <!-- ── Header ── -->
    <div class="sales-header">
        <h1>{{ __('my_purchases') }}</h1>

        <form method="get" action="{{ route('mes-achats') }}" class="filter-bar">
            <select name="month">
                <option value="">{{ __('month') }}</option>
                @foreach(['01'=>'january','02'=>'february','03'=>'march','04'=>'april','05'=>'may','06'=>'june','07'=>'july','08'=>'august','09'=>'september','10'=>'october','11'=>'november','12'=>'december'] as $val => $label)
                <option value="{{ $val }}" {{ $month == $val ? 'selected' : '' }}>{{ __($label) }}</option>
                @endforeach
            </select>

            <select name="year" id="year-select">
                <option value="">{{ __('year') }}</option>
            </select>
            <script>
                const yearSelect = document.getElementById('year-select');
                for (let y = 2024; y <= new Date().getFullYear(); y++) {
                    const opt = document.createElement('option');
                    opt.value = y; opt.textContent = y;
                    if (y == "{{ $year }}") opt.selected = true;
                    yearSelect.appendChild(opt);
                }
            </script>

            <button class="btn-filter-submit" type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                {{ __('filter') }}
            </button>
        </form>
    </div>

    <!-- ── Table Component ── -->
    @include('components.Liste-mes-achats', ['achats' => $achats])

</div>

@endsection
