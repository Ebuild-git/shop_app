@extends('User.fixe')
@section('titre', 'Historique')
@section('content')
@section('body')

    <div class="container pt-5 pb-5 ">

        <div class="card p-3">
            <div class="d-flex justify-content-between">
                <div>
                    @if ($count == 0)
                        <span class="h4">
                            Créer Votre Première publication
                            <span class="text-red">Maintenant </span> !
                        </span>
                    @else
                        <span class="h4">
                            Créer une publication
                            <span class="text-red">Maintenant </span> !
                        </span>
                    @endif
                    <div class="text-muted small">
                        {{ Auth::user()->name }} ,vous pouvez créer une <br> publication et vendre vos poduits quand
                        vous voulez !
                    </div>

                </div>
                <div style="text-align: right">
                    <a href="/publication">
                        <button type="button" class="btn btn-light btn-sm my-2 my-sm-0 btn-vend">
                            <i class="bi bi-plus-circle-fill"></i>
                            Vendre un article
                        </button>
                    </a>
                </div>
            </div>
        </div>

        <div class=" p-3">
            <div>
                <button class="btn btn-achat bg-red shadow-none" onclick="change('achat')">
                    <i class="bi bi-bag"></i>
                    Achats
                </button>
                <button class="btn btn-vente shadow-none" onclick="change('vente')">
                    <i class="bi bi-cash-coin"></i>
                    Ventes
                </button>
                <button class="btn btn-pub shadow-none" onclick="change('pub')">
                    <i class="bi bi-cash-coin"></i>
                    publications
                </button>
            </div>
            <div class="border border-1 p-3 rounded card">
                <div class="div-data" id="div-achat">
                    @livewire('User.ListeAchat')
                </div>
                <div class="div-data" id="div-vente" style="display: none">
                    @livewire('User.ListMesPosts', ["titre"=>"Mes ventes","filter"=>false,"statut"=>"vendu"])
                </div>
                <div class="div-data" id="div-pub" style="display: none">
                    @livewire('User.ListMesPosts', ["titre"=>"Mes publications","filter"=>true,"statut"=>null])
                </div>
            </div>
        </div>
    </div>
    <script>
        function change(type) {
            
            if (type == "vente") {
                document.getElementById("div-vente").style.display = "none";
                document.getElementById("div-achat").style.display = "none";
                document.getElementById("div-vente").style.display = "";
                document.getElementById("div-pub").style.display = "none";
                document.getElementsByClassName("btn-achat")[0].classList.remove("bg-red");
                document.getElementsByClassName("btn-vente")[0].classList.add("bg-red");
                document.getElementsByClassName("btn-pub")[0].classList.remove("bg-red");
            } else if (type == "achat") {
                document.getElementById("div-vente").style.display = "none";
                document.getElementById("div-achat").style.display = "";
                document.getElementsByClassName("btn-vente")[0].classList.remove("bg-red");
                document.getElementsByClassName("btn-achat")[0].classList.add("bg-red");
                document.getElementsByClassName("btn-pub")[0].classList.remove("bg-red");
                document.getElementById("div-pub").style.display = "none";
            } else {
                document.getElementById("div-vente").style.display = "none";
                document.getElementById("div-achat").style.display = "none";
                document.getElementById("div-pub").style.display = "";
                document.getElementsByClassName("btn-achat")[0].classList.remove("bg-red");
                document.getElementsByClassName("btn-vente")[0].classList.remove("bg-red");
                document.getElementsByClassName("btn-pub")[0].classList.add("bg-red");

            }
        }
    </script>
@endsection
