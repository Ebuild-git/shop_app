@extends('User.fixe')
@section('titre', 'Marketplace')
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
                            <li class="breadcrumb-item active" aria-current="page">MarketPlace</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================= Filter Wrap ============================== -->


    <!-- ======================= All Product List ======================== -->
   @livewire('User.shop', ["categorie"=>$categorie, "key" => $key])
    <!-- ======================= All Product List ======================== -->


@endsection
