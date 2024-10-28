<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>{{$data['sujet']}}</title>


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

<body>

    <div class="email-container">
        <div class="email-header">
            <img src="{{ config('app.url') }}/icons/logo.png" height="20" alt="Logo" />
        </div>
        <br>
        <div class="email-content">
                <p>
                    Vous avez reçu un nouveau message avec le sujet suivant : <strong>{{$data['sujet']}}</strong>
                </p>
                <p>
                    <strong>Message:</strong><br>
                    {{$data['message']}}
                </p>

                <span>Merci pour votre attention !</span>
                <br>
                <p>L’équipe de  <strong style="color:black;">SHOP</strong><span style="color:#008080;"><strong>IN</strong></span></p>
        </div>
    </div>
    <br><br>
    <div class="email-footer">
        <p>{{ config('app.name') }}</p>
        <p class="small">Suivez-nous sur nos réseaux sociaux !</p>
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
