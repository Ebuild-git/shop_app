@extends('User.fixe')
@section('titre', 'Historique')
@section('content')
@section('body')

<div class="container pt-5 pb-5 ">

    <div class="card p-3">
        <div class="d-flex justify-content-between">
            <div class="h4 my-auto">
                @if ($count == 0)
                Créer Votre Première publication
                <span class="text-red">Maintenant </span> !
                @else
                Créer une publication
                <span class="text-red">Maintenant </span> !
                @endif
            </div>
            <div style="text-align: right">
                <a href="/publication">
                    <button type="button" class="btn btn-light btn-sm my-2 my-sm-0 btn-vend">
                        <i class="bi bi-plus-circle-fill"></i>
                        Publier un article
                    </button>
                </a>
            </div>
        </div>
    </div>

    <div class=" p-3">
        <div>
            <a href="{{ route('historique',['type'=>'achats']) }}" class="btn btn-achat  shadow-none @if($type == "achats") bg-red @endif">
                <i class="bi bi-bag"></i>
                Mes achats
            </a>
            <a href="{{ route('historique',['type'=>'ventes']) }}" class="btn btn-vente shadow-none @if($type == "ventes") bg-red @endif">
                <i class="bi bi-cash-coin"></i>
                Mes ventes
            </a>
            <a href="{{ route('historique',['type'=>'annonces']) }}" class="btn btn-pub shadow-none @if($type == "annonces") bg-red @endif">
                <i class="bi bi-upload"></i>
                Mes annonces
            </a>
        </div>
        <br>
        <div class="border border-1 p-3 rounded card">
            @if ($type == "achats")
            <div class="div-data" id="div-achat">
                @include('components.Liste-mes-achats',["achats"=>$achats])
            </div>
            @endif
            @if ($type == "ventes")
            <div class="div-data" id="div-vente">
                @include('components.Liste-mes-posts', ['posts' => $ventes])
            </div>
            @endif
            @if ($type == "annonces")
            <div class="div-data" id="div-pub">
                @include('components.Liste-mes-posts', ['posts' => $annonces])
            </div>
            @endif
        </div>
    </div>
</div>
@endsection