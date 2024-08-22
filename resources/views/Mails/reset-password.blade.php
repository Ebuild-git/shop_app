{{-- <!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <!-- Bootstrap Font Icon CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <title>Bienvenue</title>

    <style>
        .header-image img {
            width: 100%;
        }

        .color {
            color: #008080;
        }

        .footer {
            background-color: #008080;
            color: white;
        }

        .btn-infos {
            background-color: #008080;
            color: white !important;
            font-weight: 600;
        }

        .bg-white {
            background-color: white;
        }
    </style>

</head>

<body class=" pt-5 fw-light" style="background-color: #f4f8fb;">

    <div class="container">
        <div class="text-center">
            <img src="{{ config('app.url') }}/icons/logo.png" height="20" alt srcset>
        </div>
        <div class="row">
            <div class="col-sm-6 mx-auto bg-white p-1">

                <p>
                    Bonjour {{ $user->username }}, <br>

                    Vous avez demander a réinitailiser le mot de passe de votre compte SHOPIN. <br>
                    Veuillez appuyer sur  le lien ci-dessous.
                </p>
                <a href=" {{ route('reset_password', ['token' => $token]) }} "
                    class="btn btn-infos w-100">
                    <img width="20" height="20" src="https://img.icons8.com/ios-filled/20/FFFFFF/link--v1.png"
                        alt="link--v1" />
                        Réinitailiser maintenant
                </a>
                <br>
                <p>

                    L’équipe SHOPIN
                </p>
                <br>
                <p>
                    Vous avez récu cet e-mail sans vous inscrire ? <a href=" {{ route('inscription') }} ">Cliquez ici</a>
                </p>

            </div>
        </div>
    </div>
    <br><br>
    <div class="footer p-2 text-center">
        {{ config('app.name') }}
        <br>
        <div class="small">
            Suivez-nous sur nos réseaux sociaux !
        </div>
        <div class="mb-5 mt-2">
            <a href>
                <img width="30" height="30" src="https://img.icons8.com/glyph-neue/30/FFFFFF/facebook-new.png"
                    alt="facebook-new" />
            </a>
            <a href>
                <img width="30" height="30"
                    src="https://img.icons8.com/ios-filled/30/FFFFFF/instagram-new--v1.png" alt="instagram-new--v1" />
            </a>
            <a href>
                <img width="30" height="30"
                    src="https://img.icons8.com/glyph-neue/30/FFFFFF/linkedin-circled.png" alt="linkedin-circled" />
            </a>
            <a href>
                <img width="30" height="30" src="https://img.icons8.com/ios-filled/30/FFFFFF/tiktok--v1.png"
                    alt="tiktok--v1" />
            </a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html> --}}


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Bienvenue</title>

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
            <img src="{{ config('app.url') }}/icons/logo.png" alt="Logo">
        </div>

        <div class="email-content">
            <p>Bonjour {{ $user->username }},</p>

            <p>
                Vous avez demandé à réinitialiser le mot de passe de votre compte <strong style="color:black;">SHOP</strong><span style="color:#008080;"><strong>IN</strong></span>.
                Veuillez cliquer sur le bouton ci-dessous pour continuer.
            </p>
            <a href="{{ route('reset_password', ['token' => $token]) }}" class="btn-infos">
                <img src="https://img.icons8.com/ios-filled/20/FFFFFF/link--v1.png" alt="Lien" style="vertical-align: middle; margin-right: 5px;">
                Réinitialiser maintenant
            </a>
            <br><br>
            <p>L’équipe de  <strong style="color:black;">SHOP</strong><span style="color:#008080;"><strong>IN</strong></span></p>

            <p>
                Vous avez reçu cet e-mail sans vous inscrire ?
                <a href="{{ route('inscription') }}">Cliquez ici</a>
            </p>
        </div>

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
    </div>

</body>

</html>
