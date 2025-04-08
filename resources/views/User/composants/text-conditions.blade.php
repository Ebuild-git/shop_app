<div>
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

</div>
