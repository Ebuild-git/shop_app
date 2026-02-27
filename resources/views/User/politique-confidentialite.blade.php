@extends('User.fixe')
@section('titre', 'Politique de confidentialité')
@section('content')
@section('body')

<div class="container pt-5 pb-5" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
    <div style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">

        <h2>{!! __('privacy_title') !!}</h2>

        {{-- PRÉAMBULE --}}
        <h4>{!! __('privacy_preamble_title') !!}</h4>
        <p>{!! __('privacy_preamble_content') !!}</p>

        <br>

        {{-- ARTICLE 1 - DÉFINITIONS --}}
        <h4>1. {!! __('privacy_article1_title') !!}</h4>
        <p>{!! __('privacy_article1_content') !!}</p>

        <br>

        {{-- ARTICLE 2 - DONNÉES COLLECTÉES --}}
        <h4>2. {!! __('privacy_article2_title') !!}</h4>

        <h5>{!! __('privacy_article2_direct_subtitle') !!}</h5>
        <p>
            {!! __('privacy_article2_direct_all_users') !!}
            <br><br>
            {!! __('privacy_article2_direct_sellers') !!}
            <br><br>
            {!! __('privacy_article2_direct_buyers') !!}
        </p>

        <h5>{!! __('privacy_article2_auto_subtitle') !!}</h5>
        <p>{!! __('privacy_article2_auto_content') !!}</p>

        <br>

        {{-- ARTICLE 3 - FINALITÉS --}}
        <h4>3. {!! __('privacy_article3_title') !!}</h4>

        <h5>{!! __('privacy_article3_main_subtitle') !!}</h5>
        <p>{!! __('privacy_article3_main_content') !!}</p>

        <h5>{!! __('privacy_article3_secondary_subtitle') !!}</h5>
        <p>{!! __('privacy_article3_secondary_content') !!}</p>

        <br>

        {{-- ARTICLE 4 - DESTINATAIRES --}}
        <h4>4. {!! __('privacy_article4_title') !!}</h4>

        <h5>{!! __('privacy_article4_internal_subtitle') !!}</h5>
        <p>{!! __('privacy_article4_internal_content') !!}</p>

        <h5>{!! __('privacy_article4_partners_subtitle') !!}</h5>
        <p>{!! __('privacy_article4_partners_content') !!}</p>

        <h5>{!! __('privacy_article4_no_sale_subtitle') !!}</h5>
        <p>{!! __('privacy_article4_no_sale_content') !!}</p>

        <br>

        {{-- ARTICLE 5 - SÉCURITÉ --}}
        <h4>5. {!! __('privacy_article5_title') !!}</h4>

        <h5>{!! __('privacy_article5_measures_subtitle') !!}</h5>
        <p>{!! __('privacy_article5_measures_content') !!}</p>

        <h5>{!! __('privacy_article5_encryption_subtitle') !!}</h5>
        <p>{!! __('privacy_article5_encryption_content') !!}</p>

        <h5>{!! __('privacy_article5_user_subtitle') !!}</h5>
        <p>{!! __('privacy_article5_user_content') !!}</p>

        <br>

        {{-- ARTICLE 6 - COOKIES --}}
        <h4>6. {!! __('privacy_article6_title') !!}</h4>

        <h5>{!! __('privacy_article6_what_subtitle') !!}</h5>
        <p>{!! __('privacy_article6_what_content') !!}</p>

        <h5>{!! __('privacy_article6_types_subtitle') !!}</h5>
        <p>{!! __('privacy_article6_types_content') !!}</p>

        <h5>{!! __('privacy_article6_manage_subtitle') !!}</h5>
        <p>{!! __('privacy_article6_manage_content') !!}</p>

        <br>

        {{-- ARTICLE 7 - VOS DROITS --}}
        <h4>7. {!! __('privacy_article7_title') !!}</h4>

        <p><i>{!! __('privacy_article7_intro') !!}</i></p>

        <h5>{!! __('privacy_article7_access_subtitle') !!}</h5>
        <p>{!! __('privacy_article7_access_content') !!}</p>

        <h5>{!! __('privacy_article7_rectification_subtitle') !!}</h5>
        <p>{!! __('privacy_article7_rectification_content') !!}</p>

        <h5>{!! __('privacy_article7_erasure_subtitle') !!}</h5>
        <p>{!! __('privacy_article7_erasure_content') !!}</p>

        <h5>{!! __('privacy_article7_opposition_subtitle') !!}</h5>
        <p>{!! __('privacy_article7_opposition_content') !!}</p>

        <h5>{!! __('privacy_article7_limitation_subtitle') !!}</h5>
        <p>{!! __('privacy_article7_limitation_content') !!}</p>

        <h5>{!! __('privacy_article7_exercise_subtitle') !!}</h5>
        <p>{!! __('privacy_article7_exercise_content') !!}</p>

        <br>

        {{-- ARTICLE 8 - MODIFICATIONS --}}
        <h4>8. {!! __('privacy_article8_title') !!}</h4>
        <p>{!! __('privacy_article8_content') !!}</p>

        <br>

        {{-- ARTICLE 9 - DÉCLARATION CNDP --}}
        <h4>9. {!! __('privacy_article9_title') !!}</h4>
        <p>{!! __('privacy_article9_content') !!}</p>

        <br>

        <p><small>{!! __('privacy_last_update') !!}</small></p>

    </div>
</div>

@endsection
