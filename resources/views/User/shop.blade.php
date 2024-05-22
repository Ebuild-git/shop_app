@extends('User.fixe')
@section('titre', 'Marketplace')
@section('content')
@section('body')

    <style>
        .navbar {}

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
            color: white;
            font-size: 12px;
            text-decoration: none;
        }

        .subnav-content a:hover {
            color: rgb(255, 255, 255);
            font-weight: bold;
        }
        .subnav-content button{
            background-color: unset !important;
            color: white !important;
            border: none !important;
        }
        .subnav-content button:hover{
            border-radius: 5px !important;
            background-color: white !important;
            color: #008080 !important;
            font-weight: bold !important;
            cursor: pointer;
        }
        .subnav:hover .subnav-content {
            display: block;
        }

        .list-proprietes:hover .attribut {
            display: block !important;
            transition: 0.5s !important;
        }

        .list-proprietes {
            transition: 0.5s !important;
        }
    </style>




    <div class="navbar container">
        @foreach ($categories as $cat)
            <div class="subnav">
                <button class="subnavbtn">
                    <div>
                        <img src="{{ Storage::url($cat->small_icon) }}" alt="i" class="icon" srcset="">
                    </div>
                    <span class="titre">
                        @if ($cat->luxury == true)
                            <i class="bi bi-gem color"></i>
                        @endif
                        {{ $cat->titre }}
                    </span>
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="subnav-content p-2">
                    <div class="d-flex flex-wrap">
                        @foreach ($cat->getSousCategories as $item)
                            <button type="button" class="p-1">
                                {{ $item->titre }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>



    <!-- ======================= All Product List ======================== -->
    @livewire('User.shop', ['categorie' => $categorie, 'sous_categorie' => $sous_categorie, 'key' => $key, 'luxury_only' => $luxury_only ?? null])
    <!-- ======================= All Product List ======================== -->


@endsection
