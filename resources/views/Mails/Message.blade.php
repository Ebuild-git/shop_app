<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>{!! \App\Traits\TranslateTrait::TranslateText($data['sujet']) !!}</title>

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

        .email-content a.btn-infos {
            display: inline-block;
            background-color: #008080;
            color: #ffffff !important;
            font-weight: bold;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 20px;
            margin-top: 20px;
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

        .email-footer p {
            margin: 5px 0;
        }

        .email-footer .small {
            font-size: 14px;
        }
    </style>

</head>

<body style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl; background-color: #f4f8fb;' : 'text-align: left; direction: ltr;' }}">
    <div class="email-container">
    <div class="email-header">
        <img src="{{ config('app.url') }}/icons/logo.png" height="20" alt="Logo" />
    </div>
    <br>
    <div class="email-content">
            <p>
                @lang('new_message') <strong>{!! \App\Traits\TranslateTrait::TranslateText($data['sujet']) !!}
</strong>
            </p>
            <p>
                <strong>@lang('message1')</strong><br>
                {!! \App\Traits\TranslateTrait::TranslateText($data['message']) !!}
            </p>
            <p>
                @lang('for_article') <a href="{{ route('details_post_single', ['id' => $data['post_id']]) }}" class="underlined-link">{{$data['titre']}}</a>
            </p>

            @if($data['image'])
                <p>
                    <img src="{{$data['image']}}" alt="Post Image" style="max-width: 50%; height: auto;">
                </p>
            @endif

            <span>@lang('thanks1')</span>
            <br>
            <p>@lang('team1')  <strong style="color:black;">@lang('shopin')</strong></p>
    </div>
</div>
<br><br>
<div class="email-footer">
    <p>{{ config('app.name') }}</p>
    <p class="small">@lang('follow_us')</p>
    <div class="social-icons">
        <a href="#"><img src="https://img.icons8.com/glyph-neue/30/FFFFFF/facebook-new.png" alt="Facebook"></a>
        <a href="#"><img src="https://img.icons8.com/ios-filled/30/FFFFFF/instagram-new--v1.png" alt="Instagram"></a>
        <a href="#"><img src="https://img.icons8.com/glyph-neue/30/FFFFFF/linkedin-circled.png" alt="LinkedIn"></a>
        <a href="#"><img src="https://img.icons8.com/ios-filled/30/FFFFFF/tiktok--v1.png" alt="TikTok"></a>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
