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







    <!-- ======================= All Product List ======================== -->
    @livewire('User.shop', ['categorie' => $categorie, 'sous_categorie' => $sous_categorie, 'key' => $key, 'luxury_only' => $luxury_only ?? null])
    <!-- ======================= All Product List ======================== -->


@endsection
