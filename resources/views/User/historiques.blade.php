@extends('User.fixe')
@section('titre', 'Historique')
@section('content')
@section('body')

    <div class="container pt-5 pb-5 ">

        <div class=" p-3">
            <div>
                <button class="btn btn-perso bg-red shadow-none" onclick="change('perso')">
                    <i class="bi bi-bag"></i>
                    Achats
                </button>
                <button class="btn btn-secu shadow-none" onclick="change('secu')">
                    <i class="bi bi-cash-coin"></i>
                    Ventes
                </button>
            </div>
            <div class="border border-1 p-3 rounded card">
                <div id="div-perso">
                    @livewire('User.ListeAchat')
                </div>
                <div id="div-secu" style="display: none">
                    @livewire('User.ListMesPosts')
                </div>
            </div>
        </div>
    </div>
    <script>
        function change(type) {
            if (type == "secu") {
                document.getElementById("div-perso").style.display = "none";
                document.getElementById("div-secu").style.display = "";
                document.getElementsByClassName("btn-perso")[0].classList.remove("bg-red");
                document.getElementsByClassName("btn-secu")[0].classList.add("bg-red");
            } else {
                document.getElementById("div-secu").style.display = "none";
                document.getElementById("div-perso").style.display = "";
                document.getElementsByClassName("btn-secu")[0].classList.remove("bg-red");
                document.getElementsByClassName("btn-perso")[0].classList.add("bg-red");
            }
        }
    </script>
@endsection
