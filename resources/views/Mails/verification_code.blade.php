<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>{{ __('welcome1') }}</title>
    <style>
        .color { color: #008080; }
        .footer { background-color: #008080; color: white; }
        .btn-confirm {
            background-color: #008080;
            color: white;
            font-weight: bold;
            border-radius: 20px;
            padding: 10px 20px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            font-size: 16px;
        }
        .btn-confirm:hover { background-color: #008080; color: white; }
        .code-box {
            font-size: 24px;
            font-weight: bold;
            color: #008080;
            background-color: #f0f8ff;
            padding: 10px 20px;
            border-radius: 8px;
            display: inline-block;
            margin: 15px 0;
        }
    </style>
</head>

<body class="pt-5 fw-light" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl; background-color: #f4f8fb;' : 'text-align: left; direction: ltr; background-color: #f4f8fb;' }}">
    <div class="container">
        <div class="text-center">
            <img src="{{ config('app.url') }}/icons/logo.png" height="40" alt="Logo">
        </div>

        <div class="text-center mt-3">
            <h3>{!! __('welcome', ['app' => config('app.name')]) !!}</h3>
        </div>
        <br>

        <div class="row">
            <div class="col-sm-6 mx-auto bg-white p-3">
                <p>{!! __('greeting', ['username' => $user->username]) !!}</p>
                <p>{{ __('account_created') }}</p>
                <p>{{ __('verify_email') }}</p>

                <!-- Show the 6-digit code -->
                <div class="code-box">{{ $token }}</div>

                <p>{{ __('use_code_verify_email') }}</p>

                <p>{{ __('thanks') }}<br>@lang('team1') <strong style="color:black;">@lang('shopin')</strong></p>
                <p>{{ __('ignore_notice') }}</p>
            </div>
        </div>
    </div>

    <div class="footer p-2 text-center mt-4">
        {{ config('app.name') }}
        <br>
        <div class="small">{{ __('follow_us') }}</div>
        <div class="mb-5 mt-2">
            <a href="#"><img width="30" height="30" src="https://img.icons8.com/glyph-neue/30/FFFFFF/facebook-new.png" alt="facebook-new" /></a>
            <a href="#"><img width="30" height="30" src="https://img.icons8.com/ios-filled/30/FFFFFF/instagram-new--v1.png" alt="instagram-new--v1" /></a>
            <a href="#"><img width="30" height="30" src="https://img.icons8.com/glyph-neue/30/FFFFFF/linkedin-circled.png" alt="linkedin-circled" /></a>
            <a href="#"><img width="30" height="30" src="https://img.icons8.com/ios-filled/30/FFFFFF/tiktok--v1.png" alt="tiktok--v1" /></a>
        </div>
    </div>
</body>
</html>
