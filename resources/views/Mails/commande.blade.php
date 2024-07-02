<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Merci de votre comment</title>
    <style>
        .color {
            color: #018d8d;
        }

        .text-center {
            text-align: center;
        }

        .logo {
            height: 30px;
        }

        .btn {
            background-color: #018d8d;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }

        .cad-img {
            height: 60px;
            width: 60px;
            border-radius: 5px;
            overflow: hidden;
        }

        .cad-img img {
            height: 100%;
            width: 100%;
            object-fit: cover;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .td-img {
            width: 70px;
        }
    </style>
</head>

<body>
    <div class="text-center">
        <img src="{{ config('app.url') }}/icons/logo.png" alt="logo" class="logo" srcset="">
    </div>
    <div>
        <h2 class="color">
            <b>
                {{ $user->firstname }}, merci de votre achat !
            </b>
        </h2>
        <p>
            Nous avons le plaisir de vous confirmer la prise en charge de votre commande.
        </p>
        <p>
            Vous trouverez ci-dessous le récapitulatif de votre commande que vous pouvez egalement retrouver dans votre
            compte.
        </p>
        <br>
        <br>
        <h3>
            <b>Récapitulatif</b>
        </h3>
        <table>
            @php
                $total = 0;
            @endphp
            @foreach ($articles_panier as $article)
                <tr>
                    <td class="td-img">
                        <div class="cad-img">
                            <img src="{{ $article['photo'] }}"
                                alt="{{ $article['titre'] }}" srcset="">
                        </div>
                    </td>
                    <td>
                        <b>
                            <h3>
                                {{ $article['titre'] }}
                            </h3>
                        </b>
                        <p class="color">
                            {{ $article['prix'] }} DH
                        </p>
                    </td>
                </tr>
                @php
                    $total += $article['prix'];
                @endphp
            @endforeach
        </table>
        <div>
            <h4>Frais de livraison : 0 DH</h4>
            <h3>Total : {{  $total }} DH </h3>
        </div>
        <br><br>
        <div>
            <b>Mode de paiement : </b> Paiement a la livraison. <br>
            <b>Adresse de livraison : </b> {{ $user->address }}
            <br>
            <br>
        </div>
    </div>
</body>

</html>
