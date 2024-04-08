<!DOCTYPE html>
<html lang="en">

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

        <div class="text-center mt-3">
            <h3>
                Bienvenue sur <b class="color">{{ config('app.name') }}</b> !
            </h3>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-6 mx-auto bg-white p-1">
                @if ($categorie)
                   <div class="header-image">
                    <img src="{{ url(Storage::url($categorie->icon)) }}" alt>
                </div> 
                @endif
                
                <br>
                <h5>
                    Confirmez votre inscription
                </h5>

                <p>
                    Cher(e) utilisateur/trice, <br>

                    Nous vous souhaitons la bienvenue sur <b class="color">{{ config('app.name') }}</b>, la plateforme
                    en ligne pour acheter et vendre en toute simplicité.
                    Nous sommes
                    ravis de vous compter parmi notre communauté.
                </p>
                <p>
                    Pour finaliser votre inscription, veuillez cliquer sur
                    le lien
                    ci-dessous afin de confirmer votre adresse e-mail :
                </p>
                <p class="fw-normal">
                    En cliquant sur ce lien, vous activerez votre compte et
                    pourrez
                    commencer à profiter pleinement de tous les services que
                    LeBonCoin a à vous offrir.
                </p>
                <a href=" {{ route('verify_account', ['id_user' => $user->id, 'token' => $token]) }} "
                    class="btn btn-infos w-100">
                    <img width="20" height="20" src="https://img.icons8.com/ios-filled/20/FFFFFF/link--v1.png"
                        alt="link--v1" />
                    Confirmer mon inscription
                </a>
                <br><br>
                <p>
                    Si vous n'avez pas tenté de vous inscrire à notre
                    plateforme,
                    veuillez ignorer cet e-mail.
                </p>
                <p>
                    Nous restons à votre disposition pour toute question ou
                    assistance supplémentaire. N'hésitez pas à nous
                    contacter..
                </p>
                <p>
                    Cordialement,
                </p>
                <p>
                    L'équipe <b class="color">{{ config('app.name') }}</b>
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

</html>
