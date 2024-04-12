@extends('User.fixe')
@section('titre', 'Mes Informations')
@section('content')
@section('body')

    <div class="container pt-5 pb-5 ">
        <div class="bg-red p-3 rounded mb-3">
            <div class="d-flex justify-content-between bg-red">
                <div>
                    <a href="/">
                        <button class="back-btn shadow-none">
                            <i class="bi bi-arrow-left-circle"></i>
                        </button>
                    </a>
                </div>
                <div>
                </div>
            </div>
        </div>

        <div class=" p-3">
            <div>
                <button class="btn btn-perso bg-red shadow-none" onclick="change('perso')">
                    <i class="bi bi-person"></i>
                    Informations personnelles
                </button>
                <button class="btn btn-secu shadow-none" onclick="change('secu')">
                    <i class="bi bi-lock"></i>
                    Sécurité
                </button>
            </div>
            <div class="border border-1 p-3 rounded card">
                <div id="div-perso">
                    <h4 class="text-muted">Mes informations</h4>
                    <hr>
                    @livewire('User.UpdateInformations')
                </div>
                <div id="div-secu" style="display: none">
                    <h4 class="text-muted">Sécurité</h4>
                    <hr>
                    @livewire('User.UpdateMySecurity')
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
