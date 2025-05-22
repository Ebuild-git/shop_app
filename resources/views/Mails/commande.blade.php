<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('email.thank_you1') }}</title>
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
            color: #ffffff;
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

        .content .order-summary {
            border-top: 1px solid #eeeeee;
            margin-top: 20px;
            padding-top: 20px;
        }

        .content .order-summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .content .order-summary table td {
            padding: 10px 0;
            vertical-align: top;
        }

        .content .order-summary table .product-img {
            width: 70px;
            border-radius: 5px;
            overflow: hidden;
        }

        .content .order-summary table .product-img img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }
        .content .order-summary table .product-img1 {
            width: 70px;
            border-radius: 5px;
            overflow: hidden;
        }

        .content .order-summary table .product-img1 img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }
        .content .order-summary table .product-info {
            padding-left: 15px;
        }
        .content .order-summary table .product-info h3 {
            font-size: 18px;
            margin: 0;
            color: #333;
        }

        .content .order-summary table .product-info p {
            color: #008080;
            font-weight: bold;
            margin: 5px 0 0 0;
        }

        .content .total {
            font-size: 18px;
            margin-top: 20px;
        }

        .footer {
            background-color: #008080;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
        <div class="header">
            <img src="{{ config('app.url') }}/icons/logo.png" alt="logo" class="logo" srcset="">
        </div>
        <div class="content">
            <h2>{{ __('email.thank_you', ['username' => $user->username]) }}</h2>
            <p>{{ __('email.order_received') }}</p>
            <p>{!! __('email.order_details_with_link') !!}</p>

            <div class="order-summary">
                <h3>{{ __('email.order_summary') }}</h3>
                <table>
                    @php
                        $total = 0;
                    @endphp
                    @foreach ($articles_panier as $article)
                        <tr>
                            <td class="product-img">
                                <img src="{{ $article['photo'] }}" alt="{{ $article['titre'] }}">
                            </td>
                            <td class="product-info">
                                <h3>{{ $article['titre'] }}</h3>
                                <p style="color: #555;">{{ __('email.seller') }} : <strong>{{ $article['vendeur'] ?? 'N/A' }}</strong></p>
                                <p>{{ $article['prix'] }} <sup>{{ __('currency') }}</sup></p>
                            </td>
                        </tr>
                        @php
                            $total += $article['prix'];
                        @endphp
                    @endforeach
                </table>

                <div class="total">
                    <h4>{{ __('email.shipping_fees') }} : {{ number_format($totalShippingFees, 2, '.', '') }} <sup>{{ __('currency') }}</sup></h4>
                    <h3>{{ __('email.total') }} : {{ number_format($total + $totalShippingFees, 2, '.', '') }} <sup>{{ __('currency') }}</sup></h3>
                </div>
            </div>

            <div class="delivery-info">
                <p><strong>{{ __('email.payment_method') }} :</strong> {{ __('email.payment_method1') }}</p>
                <p><strong>{{ __('email.delivery_address') }} :</strong>
                    {{ $user->rue ? $user->rue . ', ' : '' }}
                    {{ $user->nom_batiment ? $user->nom_batiment . ', ' : '' }}
                    {{ $user->etage ? 'Étage ' . $user->etage . ', ' : '' }}
                    {{ $user->num_appartement ? 'Appartement ' . $user->num_appartement . ', ' : '' }}
                    {{ $user->address }}
                </p>
                <p><strong>{{ __('email.region') }} :</strong> {{ $user->region_info->nom ?? '-' }}</p>
                <p><strong>{{ __('email.phone_number') }} :</strong> {{ $user->phone_number ?? "-"}}</p>
            </div>
        </div>
        <div class="footer">
            {{ __('email.footer') }}
        </div>
    </div>
</body>

</html>
