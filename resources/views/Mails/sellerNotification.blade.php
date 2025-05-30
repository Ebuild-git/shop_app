<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('order_notification') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 20px;
        }

        .header {
            text-align: center;
            padding: 10px 0;
            background-color: #f4f8fb;
        }

        .header img {
            height: 40px;
        }

        .content {
            padding: 20px;
        }

        .content h2 {
            color: #008080;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .content p {
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .content h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #555;
        }
        .order-summary {
            margin-top: 20px;
        }

        .order-summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .order-summary td {
            padding: 10px;
            vertical-align: middle;
        }

        .product-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .product-item img {
            width: 80px; /* Adjust image size */
            height: auto;
            margin-right: 15px; /* Space between image and text */
        }

        .product-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start; /* Ensure left alignment */
        }
        .product-info h3 {
            margin: 0 0 5px 0;
            font-size: 16px;
            color: #333;
            display: block; /* Ensure it is a block element */
        }

        .product-info .gain {
            margin: 0;
            font-weight: 600;
            font-size: 14px;
            color: #555;
            display: block; /* Ensure it is a block element */
        }

        .footer {
            background-color: #008080;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: white;
        }
        .shop, .in, .er, .pseudo {
            font-weight: bold;
        }
        .shop {
            color: black;
        }

        .in {
            color: #008080;
        }

        .er {
            color: black;
        }

        .pseudo {
            color: #008080;
        }

    </style>
</head>

<body>
    <div class="container" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
        <div class="header">
            <img src="{{ config('app.url') }}/icons/logo.png" alt="Logo" class="logo" />
        </div>

        <div class="content">
            <h2>
                {{ $salutation }} {{ $seller->username }},
            </h2>
            <p>{!! __('new_order_message', ['buyer_pseudo' => $buyerPseudo]) !!}</p>

            <div class="order-summary">
                <h3>{{ __('order_summary') }}</h3>
                <table>
                    @foreach ($articlesWithGain as $article)
                        <tr class="product-row">
                            <td class="image-cell">
                                <img src="{{ $article['photo'] }}" alt="{{ $article['titre'] }}" width="60" height="60">
                            </td>
                            <td class="details-cell">
                                <h3>{{ $article['titre'] }}</h3>
                                <span class="price-info">
                                    {{ __('receive_amount') }}
                                    <span style="color: #008080; font-size: 18px;">{{ $article['gain'] }}</span><sup style="color: #008080;">DH</sup>
                                </span>
                            </td>
                        </tr>
                    @endforeach

                </table>
            </div>

            {{-- <p>
                Merci de bien vouloir
                <a href="{{ auth()->check() ? route('mes_informations', ['section' => 'cord']) : route('login') }}" class="underlined-link">
                    cliquer ici
                </a>
                pour confirmer ou mettre à jour vos informations bancaires (RIB), afin que nous puissions vous transférer les fonds lorsque le processus de vente sera finalisé.
            </p> --}}
            <p>{!! __('bank_info_confirmation', [
                'link' => auth()->check() ? route('mes_informations', ['section' => 'cord']) : route('login')
            ]) !!}</p>
        </div>

        <div class="footer">
            {{ __('footer_thanks') }}
        </div>
    </div>
</body>


</html>
