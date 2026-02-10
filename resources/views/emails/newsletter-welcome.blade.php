<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>{{ __('newsletter_welcome_title') }}</title>
    <style>
        body {
            background-color: #f4f8fb;
            font-family: Arial, sans-serif;
            color: #333;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            text-align: center;
            padding-bottom: 20px;
        }

        .email-header img {
            height: 50px;
        }

        .email-content {
            line-height: 1.6;
        }

        .email-content p {
            margin: 0 0 10px;
        }

        .color {
            color: #008080;
        }

        .welcome-box {
            background-color: #f0f8ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }

        .welcome-box h2 {
            color: #008080;
            margin-bottom: 10px;
        }

        .benefits-list {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }

        .benefits-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .benefits-list li {
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .benefits-list li:last-child {
            border-bottom: none;
        }

        .benefits-list li:before {
            content: "✓ ";
            color: #008080;
            font-weight: bold;
            margin-right: 8px;
        }

        .cta-button {
            display: inline-block;
            background-color: #008080;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 15px 0;
        }

        .cta-button:hover {
            background-color: #006666;
            color: white;
        }

        .email-footer {
            text-align: center;
            background-color: #008080;
            color: #ffffff;
            padding: 15px;
            border-radius: 0 0 10px 10px;
            margin-top: 30px;
        }

        .email-footer .social-icons img {
            width: 30px;
            margin: 0 5px;
        }

        .email-footer .small {
            font-size: 14px;
        }

        .unsubscribe-link {
            color: #cccccc;
            font-size: 12px;
            text-decoration: none;
        }

        .unsubscribe-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body
    style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">

    <div class="email-container">
        <div class="email-header">
            <img src="{{ config('app.url') }}/icons/logo.png" alt="Logo">
        </div>

        <div class="email-content">
            <div class="welcome-box">
                <h2>{{ __('newsletter_welcome_heading') }}</h2>
                <p>{{ __('newsletter_welcome_subheading') }}</p>
            </div>

            <p>{{ __('newsletter_welcome_greeting', ['name' => $subscription->name ?? __('newsletter_dear_subscriber')]) }}
            </p>

            <p>{{ __('newsletter_welcome_message') }}</p>

            <div class="benefits-list">
                <h4 style="color: #008080; margin-bottom: 15px;">{{ __('newsletter_benefits_title') }}</h4>
                <ul>
                    <li>{{ __('newsletter_benefit_1') }}</li>
                    <li>{{ __('newsletter_benefit_2') }}</li>
                    <li>{{ __('newsletter_benefit_3') }}</li>
                    <li>{{ __('newsletter_benefit_4') }}</li>
                    <li>{{ __('newsletter_benefit_5') }}</li>
                </ul>
            </div>

            <div style="text-align: center;">
                <a href="{{ config('app.url') }}/shop" class="cta-button">
                    {{ __('newsletter_cta_button') }}
                </a>
            </div>

            <p>{{ __('newsletter_closing_message') }}</p>

            <p>{{ __('thanks') }}<br>@lang('team1') <strong style="color:black;">@lang('shopin')</strong></p>

            <hr style="border: 1px solid #e0e0e0; margin: 20px 0;">

            <p style="font-size: 12px; color: #666;">
                {{ __('newsletter_unsubscribe_info') }}<br>
                <a href="{{ $unsubscribeUrl ?? route('newsletter.unsubscribe', $subscription->unsubscribe_token ?? '') }}"
                    class="unsubscribe-link">
                    {{ __('newsletter_unsubscribe_link') }}
                </a>
            </p>
        </div>

        <div class="email-footer">
            <p><strong>{{ config('app.name') }}</strong></p>
            <p class="small">{{ __('newsletter_footer_tagline') }}</p>
            <div class="social-icons">
                <a href="#"><img src="https://img.icons8.com/glyph-neue/30/FFFFFF/facebook-new.png" alt="Facebook"></a>
                <a href="#"><img src="https://img.icons8.com/ios-filled/30/FFFFFF/instagram-new--v1.png"
                        alt="Instagram"></a>
                <a href="#"><img src="https://img.icons8.com/glyph-neue/30/FFFFFF/linkedin-circled.png"
                        alt="LinkedIn"></a>
                <a href="#"><img src="https://img.icons8.com/ios-filled/30/FFFFFF/tiktok--v1.png" alt="TikTok"></a>
            </div>
        </div>
    </div>

</body>

</html>