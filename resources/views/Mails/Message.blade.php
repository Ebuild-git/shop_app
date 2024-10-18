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

                <span>Merci de votre attention !</span>
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
