@extends('User.fixe')
@section('titre', \App\Traits\TranslateTrait::TranslateText('Comment acheter ?'))
@section('content')
@section('body')
   <!-- ======================= Top Breadcrumbs ======================== -->
   <div class="gray py-3" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
    <div class="container">
        <div class="row">
            <div class="colxl-12 col-lg-12 col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">{!! \App\Traits\TranslateTrait::TranslateText('Comment acheter?') !!}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- ======================= Top Breadcrumbs ======================== -->

    <div class="container pt-5 pb-5" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
        <h2 class="mb-2 ft-bold">{!! \App\Traits\TranslateTrait::TranslateText('Comment Acheter?') !!}</h2>
        <br>
        {{-- <div class="row">
            <div class="col-sm-8">
                <table class="">
                    <tr onmousemove="change_image(1)">
                        <td class="p-3 text-center align-top">
                            <img src="/faq/1.png" alt="" height="50">
                        </td>
                        <td>
                            <h4 class="title title-1 pointer-title">
                                <b>{!! \App\Traits\TranslateTrait::TranslateText('Explorez à votre façon') !!}</b>
                            </h4>
                            <p class="text-justified">{!! \App\Traits\TranslateTrait::TranslateText('Utilisez notre recherche multicritères pour affiner vos envies ou laissez-vous surprendre en parcourant les annonces des autres SHOP<span class="color">IN</span>ERS jusqu’à ce que vous tombiez sur le coup de cœur parfait.') !!}</p>
                        </td>
                    </tr>
                    <tr onmousemove="change_image(2)">
                        <td class="p-3 text-center align-top">
                            <img src="/faq/2.png" alt="" height="50">
                        </td>
                        <td>
                            <h4 class="title title-2 pointer-title">
                                <b>{!! \App\Traits\TranslateTrait::TranslateText('Ajoutez vos favoris d’un simple clic') !!}</b>
                            </h4>
                            <p class="text-justified">{!! \App\Traits\TranslateTrait::TranslateText('Repérez un article qui vous plaît ? Appuyez sur le petit cœur pour l’ajouter instantanément à votre liste de favoris et ne jamais perdre de vue vos pépites.') !!}</p>
                        </td>
                    </tr>
                    <tr onmousemove="change_image(3)">
                        <td class="p-3 text-center align-top">
                            <img src="/faq/3.png" alt="" height="50">
                        </td>
                        <td>
                            <h4 class="title title-3 pointer-title"><b>{!! \App\Traits\TranslateTrait::TranslateText('Validez votre panier en un rien de temps') !!}</b></h4>
                            <p class="text-justified">{!! \App\Traits\TranslateTrait::TranslateText('Et voilà c’est aussi simple que çà et le paiement se fait à la livraison') !!}</p>
                        </td>
                    </tr>
                    <tr onmousemove="change_image(4)">
                        <td class="p-3 text-center align-top">
                            <img src="/faq/4.png" alt="" height="45">
                        </td>
                        <td>
                            <h4 class="title title-4 pointer-title"><b>{!! \App\Traits\TranslateTrait::TranslateText('Recevez votre colis sans effort') !!}</b></h4>
                            <p class="text-justified">{!! \App\Traits\TranslateTrait::TranslateText('Une fois votre commande confirmée, un livreur se chargera de vous remettre votre colis directement chez vous. SHOP<span class="color">IN</span> rend votre shopping aussi pratique que plaisant!') !!}</p>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-4 text-center">
                <img src="/faq/etape-1.png" alt="f1.jpg" id="myImage"  height="400" srcset="">
            </div>
        </div> --}}
        <div class="row">
            <div class="col-sm-8">
                <table class="">
                    <tr onmousemove="change_image(1)">
                        <td class="p-3 text-center align-top">
                            <img src="/faq/1.png" alt="" height="50">
                        </td>
                        <td>
                            <h4 class="title title-1 pointer-title">
                                <b>{{ __('explore_your_way_title') }}</b>
                            </h4>
                            <p class="text-justified">{!! __('explore_your_way_description') !!}</p>
                        </td>
                    </tr>
                    <tr onmousemove="change_image(2)">
                        <td class="p-3 text-center align-top">
                            <img src="/faq/2.png" alt="" height="50">
                        </td>
                        <td>
                            <h4 class="title title-2 pointer-title">
                                <b>{{ __('add_to_favorites_title') }}</b>
                            </h4>
                            <p class="text-justified">{{ __('add_to_favorites_description') }}</p>
                        </td>
                    </tr>
                    <tr onmousemove="change_image(3)">
                        <td class="p-3 text-center align-top">
                            <img src="/faq/3.png" alt="" height="50">
                        </td>
                        <td>
                            <h4 class="title title-3 pointer-title"><b>{{ __('validate_cart_title') }}</b></h4>
                            <p class="text-justified">{{ __('validate_cart_description') }}</p>
                        </td>
                    </tr>
                    <tr onmousemove="change_image(4)">
                        <td class="p-3 text-center align-top">
                            <img src="/faq/4.png" alt="" height="45">
                        </td>
                        <td>
                            <h4 class="title title-4 pointer-title"><b>{{ __('receive_package_title') }}</b></h4>
                            <p class="text-justified">{!! __('receive_package_description') !!}</p>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-4 text-center">
                <img src="/faq/etape-1.png" alt="f1.jpg" id="myImage" height="400" srcset="">
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
            $(".title").removeClass("color");
            $(".title-"+id).addClass("color");

            var img = document.getElementById('myImage');
            img.src = "/faq/etape-" + id + ".png";
        }
    </script>
@endsection
