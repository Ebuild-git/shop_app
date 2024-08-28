@extends('User.fixe')
@section('titre', 'Comment vendre ?')
@section('content')
@section('body')
   <!-- ======================= Top Breadcrubms ======================== -->
   <div class="gray py-3">
    <div class="container">
        <div class="row">
            <div class="colxl-12 col-lg-12 col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Comment Vendre ?</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- ======================= Top Breadcrubms ======================== -->

    <div class="container pt-5 pb-5">
        <h2 class="mb-2 ft-bold">Comment Vendre ?</h2>
        <br>
        <div class="row">
            <div class="col-sm-8">
                <table class="">
                    <tr onmousemove="change_image(1)">
                        <td class="p-3 text-center">
                            <img src="/faq/1.png" alt="" height="50">
                        </td>
                        <td>
                            <h4 class="title title-1"><b>Mettez votre article en ligne en un instant</b></h4>
                            <p>Ajoutez des photos, rédigez une description, fixez le
                                prix</p>
                        </td>
                    </tr>
                    <tr onmousemove="change_image(2)">
                        <td class="p-3 text-center">
                            <img src="/faq/2.png" alt="" height="50">
                        </td>
                        <td>
                            <h4 class="title title-2"><b>Préparez votre article pour la livraison</b></h4>
                            <p>Emballez votre article. Un livreur viendra récupérer le
                                colis directement depuis chez vous</p>
                        </td>
                    </tr>
                    <tr onmousemove="change_image(3)">
                        <td class="p-3 text-center">
                            <img src="/faq/3.png" alt="" height="50">
                        </td>
                        <td>
                            <h4 class="title title-3"><b>Recevez votre argent rapidement</b></h4>
                            <p>24h après la livraison, vous receverez votre argent par
                                virement bancaire</p>
                        </td>
                    </tr>
                    <tr onmousemove="change_image(4)">
                        <td class="p-3 text-center">
                            <img src="/faq/4.png" alt="" height="45">
                        </td>
                        <td>
                            <h4 class="title title-4"><b>À vous maintenant! Achetez sur SHOP<span
                                        class="color">IN</span></b></h4>
                            <p>Explorez les beaux articles et passer à l’achat</p>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-8=4 text-center">
                <img src="/faq/etape-1.png" alt="f1.jpg" id="myImage" style="width: 100%;">
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
