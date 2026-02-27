{{--
<div style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
    <h2>{{ __('general_conditions_title') }}</h2>

<h4>1. {{ __('registration_title') }}</h4>

<p>{!! __('registration_description') !!}</p>

<br>

<h4>2. {!! \App\Traits\TranslateTrait::TranslateText('Politique tarifaire:') !!}</h4>


    <p>
        <i>
            {!! __('seller_commission_intro') !!}
        </i> <br>

        <b>
            {!! __('luxury_brand_commission') !!} <br>
            {!! __('other_category_commission') !!} <br>
        </b>

        {!! __('delivery_fees_not_included') !!}
    </p>

    <br>
    <h4>{!! __('confidentiality_title') !!}</h4>

    <p>
        {!! __('confidentiality_content') !!}
    </p>

<br>

<h4>{!! __('cookies_title') !!}</h4>

<p>
    {!! __('cookies_content') !!}
</p>

<br>


    <h4>{!! __('articles_for_sale_title') !!}</h4>

    <p>
        {!! __('articles_for_sale_content') !!}
    </p>

    <br>

    <h4>{!! __('return_title') !!}</h4>

    <p>
        {!! __('return_content') !!}
    </p>

    <br>

    <h4>{!! __('luxury_title') !!}</h4>

    <p>
        <i>{!! __('luxury_description') !!}</i><br>

        {!! __('luxury_price_condition') !!} {!! __('currency') !!} <br>

        {!! __('luxury_authentication_condition') !!} <br>

        <i>{!! __('luxury_category_condition') !!}</i> <br>

        <b>{!! __('luxury_symbol_authentication') !!} <i class="bi bi-gem" style="color: #008080;font-weight: bold"></i></b><br>

        <i>{!! __('luxury_sale_steps') !!}</i> <br>

        - {!! __('luxury_publish_item') !!} <br>

        - {!! __('luxury_purchase_confirmation') !!} <br>

        - {!! __('luxury_item_authentication') !!} <br>

        - {!! __('luxury_item_delivery') !!} <br>

        - {!! __('luxury_payment_confirmation') !!} <br>

        {!! __('luxury_return_policy') !!} <br>

        {!! __('luxury_authentication_policy') !!}
    </p>

</div> --}}
<div style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">

    <h2>{!! __('general_conditions_title1') !!}</h2>

    {{-- PRÉAMBULE --}}
    <h4>{!! __('preamble_title') !!}</h4>
    <p>{!! __('preamble_content') !!}</p>

    <br>

    {{-- ARTICLE 1 --}}
    <h4>1. {!! __('article1_title') !!}</h4>
    <p>{!! __('article1_content') !!}</p>

    <br>

    {{-- ARTICLE 2 --}}
    <h4>2. {!! __('article2_title') !!}</h4>
    <p>{!! __('article2_content') !!}</p>

    <br>

    {{-- ARTICLE 3 --}}
    <h4>3. {!! __('article3_title') !!}</h4>

    <h5>{!! __('article3_role_subtitle') !!}</h5>
    <p>{!! __('article3_role_content') !!}</p>

    <h5>{!! __('article3_responsibility_subtitle') !!}</h5>
    <p>{!! __('article3_responsibility_content') !!}</p>

    <br>

    {{-- ARTICLE 4 --}}
    <h4>4. {!! __('article4_title') !!}</h4>

    <h5>{!! __('article4_conditions_subtitle') !!}</h5>
    <p>{!! __('article4_conditions_content') !!}</p>

    <h5>{!! __('article4_process_subtitle') !!}</h5>
    <p>{!! __('article4_process_content') !!}</p>

    <h5>{!! __('article4_security_subtitle') !!}</h5>
    <p>{!! __('article4_security_content') !!}</p>

    <h5>{!! __('article4_suspension_subtitle') !!}</h5>
    <p>{!! __('article4_suspension_content') !!}</p>

    <br>

    {{-- ARTICLE 5 --}}
    <h4>5. {!! __('article5_title') !!}</h4>

    <h5>{!! __('article5_publication_subtitle') !!}</h5>
    <p>{!! __('article5_publication_content') !!}</p>

    <h5>{!! __('article5_obligations_subtitle') !!}</h5>
    <p>{!! __('article5_obligations_content') !!}</p>

    <h5>{!! __('article5_forbidden_subtitle') !!}</h5>
    <p>{!! __('article5_forbidden_content') !!}</p>

    <h5>{!! __('article5_price_subtitle') !!}</h5>
    <p>{!! __('article5_price_content') !!}</p>

    <br>

    {{-- ARTICLE 6 --}}
    <h4>6. {!! __('article6_title') !!}</h4>

    <h5>{!! __('article6_order_subtitle') !!}</h5>
    <p>{!! __('article6_order_content') !!}</p>

    <h5>{!! __('article6_confirmation_subtitle') !!}</h5>
    <p>{!! __('article6_confirmation_content') !!}</p>

    <h5>{!! __('article6_availability_subtitle') !!}</h5>
    <p>{!! __('article6_availability_content') !!}</p>

    <br>

    {{-- ARTICLE 7 --}}
    <h4>7. {!! __('article7_title') !!}</h4>

    <h5>{!! __('article7_price_subtitle') !!}</h5>
    <p>{!! __('article7_price_content') !!}</p>

    <h5>{!! __('article7_payment_subtitle') !!}</h5>
    <p>{!! __('article7_payment_content') !!}</p>

    <h5>{!! __('article7_distribution_subtitle') !!}</h5>
    <p>
        <i>{!! __('seller_commission_intro1') !!}</i><br><br>
        <b>
            {!! __('luxury_brand_commission1') !!}<br>
            {!! __('other_category_commission1') !!}<br>
        </b>
        <br>
        {!! __('delivery_fees_not_included1') !!}
    </p>

    <h5>{!! __('article7_delivery_fees_subtitle') !!}</h5>
    <p>{!! __('article7_delivery_fees_content') !!}</p>

    <br>

    {{-- ARTICLE 8 --}}
    <h4>8. {!! __('article8_title') !!}</h4>

    <h5>{!! __('article8_modalities_subtitle') !!}</h5>
    <p>{!! __('article8_modalities_content') !!}</p>

    <h5>{!! __('article8_tracking_subtitle') !!}</h5>
    <p>{!! __('article8_tracking_content') !!}</p>

    <h5>{!! __('article8_reception_subtitle') !!}</h5>
    <p>{!! __('article8_reception_content') !!}</p>

    <br>

    {{-- ARTICLE 9 --}}
    <h4>9. {!! __('article9_title') !!}</h4>

    <h5>{!! __('article9_withdrawal_subtitle') !!}</h5>
    <p>{!! __('article9_withdrawal_content') !!}</p>

    <h5>{!! __('article9_excluded_subtitle') !!}</h5>
    <p>{!! __('article9_excluded_content') !!}</p>

    <h5>{!! __('article9_return_fees_subtitle') !!}</h5>
    <p>{!! __('article9_return_fees_content') !!}</p>

    <h5>{!! __('article9_refund_subtitle') !!}</h5>
    <p>{!! __('article9_refund_content') !!}</p>

    <br>

    {{-- ARTICLE 10 --}}
    <h4>10. {!! __('article10_title') !!}</h4>

    <h5>{!! __('article10_conformity_subtitle') !!}</h5>
    <p>{!! __('article10_conformity_content') !!}</p>

    <h5>{!! __('article10_hidden_defects_subtitle') !!}</h5>
    <p>{!! __('article10_hidden_defects_content') !!}</p>

    <h5>{!! __('article10_limitation_subtitle') !!}</h5>
    <p>{!! __('article10_limitation_content') !!}</p>

    <h5>{!! __('article10_platform_subtitle') !!}</h5>
    <p>{!! __('article10_platform_content') !!}</p>

    <h5>{!! __('article10_force_majeure_subtitle') !!}</h5>
    <p>{!! __('article10_force_majeure_content') !!}</p>

    <br>

    {{-- ARTICLE 11 --}}
    <h4>11. {!! __('article11_title') !!}</h4>

    <h5>{!! __('article11_general_subtitle') !!}</h5>
    <p>{!! __('article11_general_content') !!}</p>

    <h5>{!! __('article11_forbidden_subtitle') !!}</h5>
    <p>{!! __('article11_forbidden_content') !!}</p>

    <h5>{!! __('article11_sanctions_subtitle') !!}</h5>
    <p>{!! __('article11_sanctions_content') !!}</p>

    <br>

    {{-- ARTICLE 12 --}}
    <h4>12. {!! __('article12_title') !!}</h4>

    <h5>{!! __('article12_platform_subtitle') !!}</h5>
    <p>{!! __('article12_platform_content') !!}</p>

    <h5>{!! __('article12_license_subtitle') !!}</h5>
    <p>{!! __('article12_license_content') !!}</p>

    <h5>{!! __('article12_ugc_subtitle') !!}</h5>
    <p>{!! __('article12_ugc_content') !!}</p>

    <br>

    {{-- ARTICLE 13 --}}
    <h4>13. {!! __('article13_title') !!}</h4>
    <p>{!! __('article13_content') !!}</p>

    <br>

    {{-- ARTICLE 14 --}}
    <h4>14. {!! __('article14_title') !!}</h4>
    <p>{!! __('article14_content') !!}</p>

    <br>

    {{-- ARTICLE 15 --}}
    <h4>15. {!! __('article15_title') !!}</h4>

    <h5>{!! __('article15_contact_subtitle') !!}</h5>
    <p>{!! __('article15_contact_content') !!}</p>

    <h5>{!! __('article15_law_subtitle') !!}</h5>
    <p>{!! __('article15_law_content') !!}</p>

    <br>

    {{-- ARTICLE 16 --}}
    <h4>16. {!! __('article16_title') !!}</h4>

    <h5>{!! __('article16_partial_subtitle') !!}</h5>
    <p>{!! __('article16_partial_content') !!}</p>

    <h5>{!! __('article16_entirety_subtitle') !!}</h5>
    <p>{!! __('article16_entirety_content') !!}</p>

    <h5>{!! __('article16_waiver_subtitle') !!}</h5>
    <p>{!! __('article16_waiver_content') !!}</p>

    <h5>{!! __('article16_language_subtitle') !!}</h5>
    <p>{!! __('article16_language_content') !!}</p>

    <br>

    {{-- ARTICLE 17 --}}
    <h4>17. {!! __('article17_title') !!}</h4>
    <p>{!! __('article17_content') !!}</p>

    <br>

    {{-- SECTION LUXE SHOPIN --}}
    <h4>{!! __('luxury_title1') !!}</h4>
    <p>
        <i>{!! __('luxury_description1') !!}</i><br><br>

        {!! __('luxury_price_condition1') !!} {!! __('currency1') !!}<br><br>

        {!! __('luxury_authentication_condition1') !!}<br><br>

        <i>{!! __('luxury_category_condition1') !!}</i><br>

        <b>
            {!! __('luxury_symbol_authentication1') !!}
            <i class="bi bi-gem" style="color: #008080; font-weight: bold;"></i>
        </b><br><br>

        <i>{!! __('luxury_sale_steps1') !!}</i><br>

        &mdash; {!! __('luxury_publish_item1') !!}<br>
        &mdash; {!! __('luxury_purchase_confirmation1') !!}<br>
        &mdash; {!! __('luxury_item_authentication1') !!}<br>
        &mdash; {!! __('luxury_item_delivery1') !!}<br>
        &mdash; {!! __('luxury_payment_confirmation1') !!}<br><br>

        {!! __('luxury_return_policy1') !!}<br><br>

        {!! __('luxury_authentication_policy1') !!}
    </p>

    <br>

    <p><small>{!! __('last_update') !!}</small></p>

</div>
