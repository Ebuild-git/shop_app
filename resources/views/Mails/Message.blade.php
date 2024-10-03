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
    <title>{{$data['sujet']}}</title>

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

        .bg-white {
            background-color: white;
        }
    </style>

</head>

<body class="pt-5 fw-light" style="background-color: #f4f8fb;">

    <div class="container">
        <div class="text-center">
            <img src="{{ config('app.url') }}/icons/logo.png" height="20" alt="Logo" />
        </div>

        <div class="text-center mt-3">
            <h3 class="color">{{ config('app.name') }}</h3>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-8 mx-auto bg-white p-3">
                {{-- <p>
                    Bonjour {{ $user->username }}, <br>
                </p> --}}
                <p>
                    Vous avez reçu un nouveau message avec le sujet suivant : <strong>{{$data['sujet']}}</strong>
                </p>
                <p>
                    <strong>Message :</strong><br>
                    {{$data['message']}}
                </p>

                <p>
                    Merci de votre attention !
                    <br>
                    L’équipe de <strong style="color:black;">{{ config('app.name') }}</strong>
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
            <a href="#">
                <img width="30" height="30" src="https://img.icons8.com/glyph-neue/30/FFFFFF/facebook-new.png"
                    alt="facebook-new" />
            </a>
            <a href="#">
                <img width="30" height="30"
                    src="https://img.icons8.com/ios-filled/30/FFFFFF/instagram-new--v1.png" alt="instagram-new--v1" />
            </a>
            <a href="#">
                <img width="30" height="30"
                    src="https://img.icons8.com/glyph-neue/30/FFFFFF/linkedin-circled.png" alt="linkedin-circled" />
            </a>
            <a href="#">
                <img width="30" height="30" src="https://img.icons8.com/ios-filled/30/FFFFFF/tiktok--v1.png"
                    alt="tiktok--v1" />
            </a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
