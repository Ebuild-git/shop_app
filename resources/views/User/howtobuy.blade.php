@extends('User.fixe')
@section('titre', 'Comment acheter ?')
@section('content')
@section('body')
   <!-- ======================= Top Breadcrubms ======================== -->
   <div class="gray py-3">
    <div class="container">
        <div class="row">
            <div class="colxl-12 col-lg-12 col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Comment acheter?</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- ======================= Top Breadcrubms ======================== -->

    <div class="container pt-5 pb-5">
        <h2 class="mb-2 ft-bold">Comment Acheter?</h2>
        <br>
        <div class="row">
            <div class="col-sm-8">
                <table class="">
                    <tr onmousemove="change_image(1)">
                        <td class="p-3 text-center">
                            <img src="/faq/1.png" alt="" height="50">
                        </td>
                        <td>
                            <h4 class="title title-1">
                                <b>Utilisez la recherche multicritères ou simplement parcourez les
                                annonces des autres SHOP<span class="color">IN</span>ERS</b>
                            </h4>
                            <p>Continuez jusqu’à ce que vous trouvez votre coup de coeur</p>
                        </td>
                    </tr>
                    <tr onmousemove="change_image(2)">
                        <td class="p-3 text-center">
                            <img src="/faq/2.png" alt="" height="50">
                        </td>
                        <td>
                            <h4 class="title title-2">
                                <b>Laissez un petit Coeur sur l’article qui vous plait</b>
                            </h4>
                            <p>Comme çà l’article sera ajouter à votre liste de coup de
                                coeur</p>
                        </td>
                    </tr>
                    <tr onmousemove="change_image(3)">
                        <td class="p-3 text-center">
                            <img src="/faq/3.png" alt="" height="50">
                        </td>
                        <td>
                            <h4 class="title title-3"><b>Appuyez sur « J’achète »</b></h4>
                            <p>Et voilà c’est aussi simple que çà et le paiement se fait à la
                                livraison</p>
                        </td>
                    </tr>
                    <tr onmousemove="change_image(4)">
                        <td class="p-3 text-center">
                            <img src="/faq/4.png" alt="" height="45">
                        </td>
                        <td>
                            <h4 class="title title-4"><b>Attendez votre colis</h4>
                            <p>ExploAprès avoir confirmé la réception de votre colis, un livreur
                                se rendra chez vous pour le remettre</p>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-4 text-center">
                <img src="/faq/etape-1.png" alt="f1.jpg" id="myImage"  height="400" srcset="">
            </div>
        </div>
    </div>


    <style>
        .title:hover {
            color: #008080;
        }
    </style>

    <script>
        function change_image(id) {
            //alert(id);
            $(".title").removeClass("color");
            $(".title-"+id).addClass("color");

            //change image source
            var img = document.getElementById('myImage');
            img.src = "/faq/etape-" + id + ".png";
        }
    </script>
@endsection
