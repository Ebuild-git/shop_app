@extends('User.fixe')
@section('titre', 'Shopiners')
@section('content')
@section('body')


    <!-- ======================= Shop Style 1 ======================== -->
    <section class="bg-cover" style="background:url('/icons/shop3.webp') no-repeat;">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="text-left py-5 mt-3 mb-3">
                        <h1 class="ft-medium mb-3 text-white">Shop<span class="color">in</span>ers</h1>
                        {{-- <ul class="shop_categories_list m-0 p-0">
                            <li><a href="#">Men</a></li>
                            <li><a href="#">Speakers</a></li>
                            <li><a href="#">Women</a></li>
                            <li><a href="#">Accessories</a></li>
                        </ul> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ======================= Shop Style 1 ======================== -->


    <!-- ======================= Filter Wrap Style 1 ======================== -->
    <section class="py-3 br-bottom br-top">
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
    </section>
    <!-- ============================= Filter Wrap ============================== -->


    <!-- ======================= All Product List ======================== -->
    <div>
        <div class="container pt-5 pb-5">
            <div class="row">
                @forelse ($shopiners as $shopiner)
                    <div class="col-sm-4">
                        <div class="card p-2 position-relative">
                            <img width="30" height="30" class="shopinner-cup-image"
                                src="https://img.icons8.com/external-vitaliy-gorbachev-fill-vitaly-gorbachev/30/FAB005/external-cup-award-vitaliy-gorbachev-fill-vitaly-gorbachev-9.png" />
                            <div>
                                <div class="d-flex justify-content-start">
                                    <div class=" card-image-shopiner">
                                        @if ($shopiner->avatar != "")
                                            <img src="{{ Storage::url($shopiner->avatar) }}" alt="...">
                                        @else
                                            <img src="https://t3.ftcdn.net/jpg/05/00/54/28/360_F_500542898_LpYSy4RGAi95aDim3TLtSgCNUxNlOlcM.jpg"
                                                alt="">
                                        @endif
                                    </div>
                                    <div class="pl-3" style="text-align: left">
                                        <div>
                                            <h4 class="h6">
                                                {{ '@' . $shopiner->username }}
                                            </h4>
                                        </div>
                                        <div>
                                            publications :{{ $shopiner->GetPosts->count() }} <br>
                                            Total des ventes :
                                            {{ $shopiner->GetPosts->where('sell_at', '!=', null)->count() }}
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2 note-shopinner-bas">
                                    <div>
                                        <i class="bi bi-star-fill" style="color: #ffb74e;"></i>
                                        5.5
                                    </div>
                                    <div>
                                        <i class="bi bi-bag"></i> Invendus
                                    </div>
                                    <div>
                                        <i class="bi bi-grid-3x3-gap-fill"></i>
                                        Catégories
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
    <!-- ======================= All Product List ======================== -->


@endsection
