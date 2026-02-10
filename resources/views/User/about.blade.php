@extends('User.fixe')
@section('titre', 'À propos')
@section('body')
    <!-- ======================= Top Breadcrubms ======================== -->
    <div class="gray py-3" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
        <div class="container">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/" aria-label="{{ __('home') }}"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('about')}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ======================= Top Breadcrubms ======================== -->

    <!-- ======================= Product Detail ======================== -->

    <div class="container mb-4 mt-4" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
        <!-- Heading -->
        <h2 class="mb-2 ft-bold">{!! __('about.shopin') !!}</h2>
        <!-- Text -->
        <p class="ft-regular fs-md mb-2">
           {!! __('welcome.shopin') !!}
        </p>
        <p class="ft-regular fs-md mb-2">
            {!! __('why.shopin') !!}
        </p>

        <p class="ft-regular fs-md mb-2">
            {!! __('join.shopin') !!}
        </p>
        <p class="ft-regular fs-md mb-2">
            {!! __('call.to.action') !!}
        </p>
    </div>

    <br><br>
    <!-- ======================= Product Detail End ======================== -->

@endsection
