@extends('User.fixe')
@section('titre', 'Politique des cookies')
@section('content')
@section('body')

<style>
    .cookie-content {
        font-family: 'Times New Roman', Times, serif;
        font-size: 0.8rem; /* Very small font */
        line-height: 1.2; /* Extremely tight spacing */
        margin: 0 auto;
    }

    /* Remove all margins and padding */
    .cookie-content * {
        margin: 0;
        padding: 0;
    }

    /* Main title */
    .cookie-content h2 {
        font-family: 'Times New Roman', Times, serif;
        font-size: 1.1rem;
        font-weight: 600; /* Slight bold */
        margin: 0.5rem 0 0.3rem 0;
        color: #333;
    }

    /* Section titles */
    .cookie-content h4 {
        font-family: 'Times New Roman', Times, serif;
        font-size: 0.9rem;
        font-weight: 600; /* Slight bold */
        margin: 0.4rem 0 0.2rem 0;
        color: #444;
    }

    /* Sub-titles */
    .cookie-content h5 {
        font-family: 'Times New Roman', Times, serif;
        font-size: 0.85rem;
        font-weight: 500; /* Medium bold */
        margin: 0.3rem 0 0.1rem 0;
        color: #555;
    }

    /* Paragraphs - no spacing */
    .cookie-content p {
        font-family: 'Times New Roman', Times, serif;
        margin: 0;
        padding: 0;
        font-weight: 400;
        color: #666;
        line-height: 1.2;
    }

    /* Lists - no spacing */
    .cookie-content ul {
        font-family: 'Times New Roman', Times, serif;
        margin: 0;
        padding-left: 1.2rem; /* Minimal indent */
        list-style-type: disc;
    }

    .cookie-content li {
        font-family: 'Times New Roman', Times, serif;
        margin: 0;
        padding: 0;
        font-weight: 400;
        color: #666;
        line-height: 1.2;
        font-size: 0.8rem;
    }

    /* Small text */
    .cookie-content small {
        font-family: 'Times New Roman', Times, serif;
        font-size: 0.7rem;
        color: #888;
        display: block;
        margin-top: 0.5rem;
    }

    /* Remove all breaks and spacing */
    .cookie-content br {
        display: none;
    }

    /* Remove container padding */
    .container.pt-5,
    .container.pb-5 {
        padding: 1rem !important;
    }

    /* Bold text within paragraphs - slight bold */
    .cookie-content strong,
    .cookie-content b {
        font-weight: 500; /* Not too bold */
        color: #555;
    }

    /* Make lists even more compact */
    .cookie-content ul ul {
        margin: 0;
        padding-left: 1rem;
    }

    /* No spacing between consecutive elements */
    .cookie-content h4 + h5,
    .cookie-content h5 + p,
    .cookie-content p + p,
    .cookie-content p + ul,
    .cookie-content ul + p,
    .cookie-content ul + h5 {
        margin-top: 0;
    }
</style>

<div class="container pt-5 pb-5 cookie-content" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
    <div style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">

        <h2>{!! __('c_t_1') !!}</h2>

        {{-- 6.1 Qu'est-ce qu'un cookie ? --}}
        <h4>{!! __('c_t_2') !!}</h4>
        <p>{!! __('c_d_1') !!}</p>

        {{-- 6.2 Types de cookies utilisés --}}
        <h4>{!! __('c_t_3') !!}</h4>

        <h5>{!! __('c_t_4') !!}</h5>
        <ul>
            <li>{!! __('c_d_2') !!}</li>
            <li>{!! __('c_d_3') !!}</li>
            <li>{!! __('c_d_4') !!}</li>
        </ul>

        <h5>{!! __('c_t_5') !!}</h5>
        <ul>
            <li>{!! __('c_d_5') !!}</li>
            <li>{!! __('c_d_6') !!}</li>
        </ul>

        <h5>{!! __('c_t_6') !!}</h5>
        <ul>
            <li>{!! __('c_d_7') !!}</li>
            <li>{!! __('c_d_8') !!}</li>
        </ul>

        {{-- 6.3 Gestion des cookies --}}
        <h4>{!! __('c_t_7') !!}</h4>
        <p>{!! __('c_d_9') !!}</p>
        <p>{!! __('c_d_10') !!}</p>
        <ul>
            <li>{!! __('c_d_11') !!}</li>
            <li>{!! __('c_d_12') !!}</li>
            <li>{!! __('c_d_13') !!}</li>
        </ul>
        <p><strong>{!! __('c_d_14') !!}</strong></p>

        <p><small>{!! __('c_d_15')  !!}</small></small></p>
    </div>
</div
@endsection
