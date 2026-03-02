@extends('User.fixe')
@section('titre', 'Politique de confidentialité')
@section('content')
@section('body')

<style>
    .privacy-content {
        font-family: 'Times New Roman', Times, serif;
        font-size: 0.8rem; /* Very small font */
        line-height: 1.2; /* Extremely tight spacing */
        margin: 0 auto;
        color: #000000; /* Black text for entire container */
    }

    .privacy-content h2 {
        font-family: 'Times New Roman', Times, serif;
        font-size: 1.5rem;
        font-weight: 600; /* Semi-bold instead of bold (700) */
        margin-bottom: 1.2rem;
        color: #000000; /* Black */
    }

    .privacy-content h4 {
        font-family: 'Times New Roman', Times, serif;
        font-size: 1.2rem;
        font-weight: 600; /* Semi-bold */
        margin: 1rem 0 0.5rem 0;
        color: #000000; /* Black */
    }

    .privacy-content h5 {
        font-family: 'Times New Roman', Times, serif;
        font-size: 1rem;
        font-weight: 600; /* Semi-bold */
        margin: 0.8rem 0 0.3rem 0;
        color: #000000; /* Black */
    }

    .privacy-content p {
        font-family: 'Times New Roman', Times, serif;
        margin-bottom: 0.6rem;
        font-weight: 400;
        color: #000000; /* Black */
    }

    .privacy-content small {
        font-family: 'Times New Roman', Times, serif;
        font-size: 0.8rem;
        color: #000000; /* Black */
    }

    .privacy-content i,
    .privacy-content em {
        font-family: 'Times New Roman', Times, serif;
        font-style: italic;
        color: #000000; /* Black */
    }

    .privacy-content ul,
    .privacy-content li {
        color: #000000; /* Black for lists */
    }

    .privacy-content strong,
    .privacy-content b {
        color: #000000; /* Black for bold text */
        font-weight: 600;
    }

    .privacy-content a {
        color: #000000; /* Black for links (though links should typically be distinguishable) */
        text-decoration: underline;
    }

    .privacy-content a:hover {
        color: #333333; /* Slightly lighter black on hover */
    }

    .privacy-content br {
        display: none;
    }

    .privacy-content .section-break {
        margin: 0.5rem 0;
    }
</style>

<div class="container pt-5 pb-5 privacy-content" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
    <div style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}; color: #000000;">

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

        <p><small>{!! __('c_d_15')  !!}</small></p>
    </div>
</div>

@endsection
