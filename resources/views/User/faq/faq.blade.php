@extends('User.fixe')
@section('titre', 'Comment vendre ?')
@section('content')
@section('body')
   <!-- ======================= Top Breadcrubms ======================== -->
   <div class="gray py-3" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
    <div class="container">
        <div class="row">
            <div class="colxl-12 col-lg-12 col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('how_sell') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- ======================= Top Breadcrubms ======================== -->

    <div class="container pt-5 pb-5" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
        <h2 class="mb-2 ft-bold">{{ __('how_sell') }}</h2>
        <br>
        <div class="row">
            <div class="col-sm-8">
                <table>
                    <tr onmousemove="change_image(1)">
                        <td class="p-3 text-center align-top">
                            <img src="/faq/1.png" alt="" height="50">
                        </td>
                        <td>
                            <h4 class="title title-1 pointer-title"><b>{{ __('step_1_title') }}</b></h4>
                            <p class="text-justified">{{ __('step_1_description') }}</p>
                        </td>
                    </tr>
                    <tr onmousemove="change_image(2)">
                        <td class="p-3 text-center align-top">
                            <img src="/faq/2.png" alt="" height="50">
                        </td>
                        <td>
                            <h4 class="title title-2 pointer-title"><b>{{ __('step_2_title') }}</b></h4>
                            <p class="text-justified">{{ __('step_2_description') }}</p>
                        </td>
                    </tr>
                    <tr onmousemove="change_image(3)">
                        <td class="p-3 text-center align-top">
                            <img src="/faq/3.png" alt="" height="50">
                        </td>
                        <td>
                            <h4 class="title title-3 pointer-title"><b>{{ __('step_3_title') }}</b></h4>
                            <p class="text-justified">{{ __('step_3_description') }}</p>
                        </td>
                    </tr>
                    <tr onmousemove="change_image(4)">
                        <td class="p-3 text-center align-top">
                            <img src="/faq/4.png" alt="" height="45">
                        </td>
                        <td>
                            <h4 class="title title-4 pointer-title"><b>{{ __('step_4_title') }}</b></h4>
                            <p class="text-justified">{!! __('step_4_description') !!}</p>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-4 text-center">
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
