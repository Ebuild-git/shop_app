@extends('User.fixe')
@section('titre', 'Shopiners')
@section('content')
@section('body')




    <!-- ======================= Filter Wrap Style 1 ======================== -->
    <div class="gray py-3">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/">Accueil</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Shopiners</li>
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
