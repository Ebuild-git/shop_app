<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Notification de commande</title>
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
    <div class="container">
        <div class="header">
            <img src="{{ config('app.url') }}/icons/logo.png" alt="logo" class="logo" />
        </div>
        <div class="content">
            <h2>{{ $seller->gender === 'female' ? 'Chère' : 'Cher' }} {{ $seller->username }},</h2>
            <p>
                Nous vous informons que votre article "{{ $post->titre }}" a été commandé par {{ $buyerPseudo }}.
                Veuillez préparer l'article pour l'expédition. Un livreur de notre partenaire logistique vous contactera bientôt et passera pour récupérer l'article.
            </p>
            <p>
                Merci de bien vouloir <a href="{{ config('app.url') }}/informations?section=cord" class="underlined-link">cliquer ici</a> pour confirmer ou mettre à jour vos informations bancaires (RIB), afin que nous puissions vous transférer les fonds lorsque le processus de vente sera finalisé.
            </p>
        </div>
        <div class="footer">
            Merci de votre confiance et à bientôt !
        </div>
    </div>
</body>

</html>