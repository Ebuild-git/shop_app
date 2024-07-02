<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Merci de votre comment</title>
</head>
<body>
    
    <div>
        <h5>
            {{ $use->firstname }}, merci  de votre achat !
        </h5>
        <p>
            Nous avons le plaisir de vous confirmer la prise en charge de votre commande.
        </p>
        <p>
            Vous trouverez ci-dessous le récapitulatif  de votre commande que vous pouvez egalement retrouver dans votre compte.
        </p>
        <hr>
        <table>

        </table>
        <div>
            <b>Mode de paiement : </b> Paiement a la livraison. <br>
            <b>Adresse de livraison : </b> {{ $user->adsress}}
            <br>
            <br>
            <a href="">
                Je consulte ma commande
            </a>
        </div>
    </div>
</body>
</html>