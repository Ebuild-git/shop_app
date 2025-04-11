@extends('User.fixe')
@section('titre', 'Shopiners')
@section('content')
@section('body')




    <!-- ======================= Filter Wrap Style 1 ======================== -->
    <div class="gray py-3" dir="{{ in_array(App::getLocale(), ['ar', 'fa']) ? 'rtl' : 'ltr' }}">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/"><i class="fas fa-home"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Shopiners')}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================= Filter Wrap ============================== -->


    <!-- ======================= All Product List ======================== -->
    <div>
        <div class="container pt-5 pb-5">
            @livewire('User.Shopinners')
        </div>
    </div>



    <!-- ======================= All Product List ======================== -->


@endsection
<style>
    .custom-select {
    background-color: #fff;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    padding: 0.375rem 1.75rem 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-clip: padding-box;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    appearance: none;
    position: relative;
}

.custom-select::after {
    content: "\25BC";
    position: absolute;
    top: 50%;
    right: 0.75rem;
    pointer-events: none;
    transform: translateY(-50%);
    font-size: 1rem;
    color: #495057;
}

.custom-select:hover, .custom-select:focus {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

</style>
