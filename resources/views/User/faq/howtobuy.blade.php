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
                        <li class="breadcrumb-item"><a href="/" aria-label="{{ __('home') }}"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">{!! \App\Traits\TranslateTrait::TranslateText('Comment acheter?') !!}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- ======================= Top Breadcrumbs ======================== -->

    <div class="container pt-5 pb-5" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
        <h2 class="mb-2 ft-bold">{!! \App\Traits\TranslateTrait::TranslateText('Comment Acheter?') !!}</h2>
        <br>
        <div class="row">
            <div class="col-sm-8">
                <table class="">
                    <tr onmousemove="change_image(1)">
                        <td class="p-3 text-center align-top">
                            <img src="/achat/1.png" alt="" height="50">
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
                            <img src="/achat/2.png" alt="" height="50">
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
                            <img src="/achat/3.png" alt="" height="50">
                        </td>
                        <td>
                            <h4 class="title title-3 pointer-title"><b>{{ __('validate_cart_title') }}</b></h4>
                            <p class="text-justified">{{ __('validate_cart_description') }}</p>
                        </td>
                    </tr>
                    <tr onmousemove="change_image(4)">
                        <td class="p-3 text-center align-top">
                            <img src="/achat/4.png" alt="" height="45">
                        </td>
                        <td>
                            <h4 class="title title-4 pointer-title"><b>{{ __('receive_package_title') }}</b></h4>
                            <p class="text-justified">{!! __('receive_package_description') !!}</p>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-4 d-flex justify-content-center align-items-center">
                <img id="myImage" src="/achat/etape-1.jpg" alt="f1.jpg" class="img-fluid rounded" style="max-height: 400px;">
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
        $(".title-" + id).addClass("color");

        var img = document.getElementById('myImage');

        img.onerror = function () {
            img.onerror = null;
            img.src = "/achat/etape-" + id + ".jpg";
        };

        img.src = "/achat/etape-" + id + ".png";
    }
    </script>
@endsection
