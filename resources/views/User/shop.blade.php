@extends('User.fixe')
@section('titre', 'Marketplace')
@section('content')
@section('body')



    <style>
        .navbar {
        }

        .navbar .icon {
            height: 30px;
        }

        .navbar .titre {
            font-size: 12px !important;
        }

        .navbar .fa-caret-down {
            display: none;
        }

        .navbar a {
            float: left;
            font-size: 16px;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .navbar .subnav:hover .fa-caret-down {
            display: block;
        }

        .subnav {
            overflow: hidden;
        }

        .subnav .subnavbtn {
            font-size: 16px;
            border: none;
            outline: none;
            background-color: inherit;
            font-family: inherit;
            margin: 0;
        }

        .navbar a:hover,
        .subnav:hover .subnavbtn {
            border-bottom: solid 2px #008080;
        }

        .subnav-content {
            display: none;
            position: absolute;
            left: 0;
            background-color: #008080;
            width: 100%;
            z-index: 1;
        }

        .subnav-content a {
            text-align: left !important;
            color: white;
            font-size: 12px;
            text-decoration: none;
        }

        .subnav-content a:hover {
            color: rgb(255, 255, 255);
            font-weight: bold;
        }

        .subnav:hover .subnav-content {
            display: block;
        }
    </style>

    <!-- ======================= Filter Wrap Style 1 ======================== -->

    <div class="navbar container">
        @foreach ($categories as $cat)
            <div class="subnav">
                <button class="subnavbtn">
                    <div>
                        <img src="{{ Storage::url($cat->small_icon) }}" alt="i" class="icon" srcset="">
                    </div>
                    <span class="titre">
                        {{ $cat->titre }}
                    </span>
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="subnav-content">
                    @foreach ($cat->getSousCategories as $item)
                        <a href="#bring">
                            {{ $item->titre }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>






    <!-- ============================= Filter Wrap ============================== -->


    <!-- ======================= All Product List ======================== -->
    @livewire('User.shop', ['categorie' => $categorie, 'sous_categorie' => $sous_categorie, 'key' => $key, 'luxury_only' => $luxury_only ?? null])
    <!-- ======================= All Product List ======================== -->


@endsection
