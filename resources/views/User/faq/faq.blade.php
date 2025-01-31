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
                        <li class="breadcrumb-item active" aria-current="page">{{ \App\Traits\TranslateTrait::TranslateText('Comment Vendre ?') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- ======================= Top Breadcrubms ======================== -->

    <div class="container pt-5 pb-5">
        <h2 class="mb-2 ft-bold">{{ \App\Traits\TranslateTrait::TranslateText('Comment Vendre ?') }}</h2>
        <br>
        <div class="row">
            <div class="col-sm-8">
                <table>
                    <tr onmousemove="change_image(1)">
                        <td class="p-3 text-center align-top">
                            <img src="/faq/1.png" alt="" height="50">
                        </td>
                        <td>
                            <h4 class="title title-1 pointer-title"><b>{{ \App\Traits\TranslateTrait::TranslateText('Mettez votre article en ligne en un clin d\'œil') }}</b></h4>
                            <p class="text-justified">{{ \App\Traits\TranslateTrait::TranslateText('Ajoutez des photos attrayantes, décrivez votre article avec soin, et fixez un prix compétitif.') }}</p>
                        </td>
                    </tr>
                    <tr onmousemove="change_image(2)">
                        <td class="p-3 text-center align-top">
                            <img src="/faq/2.png" alt="" height="50">
                        </td>
                        <td>
                            <h4 class="title title-2 pointer-title"><b>{{ \App\Traits\TranslateTrait::TranslateText('Préparez votre article pour la livraison') }}</b></h4>
                            <p class="text-justified">{{ \App\Traits\TranslateTrait::TranslateText('Emballez soigneusement votre article. Un livreur viendra le récupérer directement chez vous, sans effort de votre part.') }}</p>

                        </td>
                    </tr>
                    <tr onmousemove="change_image(3)">
                        <td class="p-3 text-center align-top">
                            <img src="/faq/3.png" alt="" height="50">
                        </td>
                        <td>
                            <h4 class="title title-3 pointer-title"><b>{{ \App\Traits\TranslateTrait::TranslateText('Recevez votre argent rapidement') }}</b></h4>
                            <p class="text-justified">{{ \App\Traits\TranslateTrait::TranslateText('24 heures après la livraison réussie, votre paiement sera effectué par versement selon les modalités convenues.') }}</p>
                        </td>
                    </tr>
                    <tr onmousemove="change_image(4)">
                        <td class="p-3 text-center align-top">
                            <img src="/faq/4.png" alt="" height="45">
                        </td>
                        <td>
                            <h4 class="title title-4 pointer-title"><b>{{ \App\Traits\TranslateTrait::TranslateText('C’est à vous de jouer !') }}</b></h4>
                            <p class="text-justified">{!! \App\Traits\TranslateTrait::TranslateText('Rejoignez SHOP<span class="color">IN</span> dès aujourd’hui, vendez vos articles, et découvrez des trésors uniques à acheter.') !!}</p>
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
