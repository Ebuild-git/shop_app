@extends('User.fixe')
@section('titre', 'Adresse de livraison')
@section('body')
    <!-- ======================= Top Breadcrubms ======================== -->
    <div class="gray py-3" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
        <div class="container">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/" aria-label="{{ __('home') }}"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('shipping_address')}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    @livewire('User.AdresseInfo')
@endsection
