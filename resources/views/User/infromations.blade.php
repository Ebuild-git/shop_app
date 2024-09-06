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
        <div class="p-3">
            <div>
                <button class="btn btn-perso bg-red shadow-none" onclick="change('perso')">
                    <i class="bi bi-person"></i>
                    Informations personnelles
                </button>
                <button class="btn btn-cord shadow-none" onclick="change('cord')">
                    <i class="fas fa-credit-card"></i>
                    Coordonnées bancaires
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
                <div id="div-cordonnées" style="display: none;">
                    <h4 class="text-muted">Mes coordonnées bancaires</h4>
                    <hr>
                    @livewire('User.UpdateCordonnées')
                </div>
                <div id="div-secu" style="display: none;">
                    <h4 class="text-muted">Sécurité</h4>
                    <hr>
                    @livewire('User.UpdateMySecurity')
                </div>
            </div>
        </div>
    </div>
    <script>
        function change(type) {
            // Hide all sections
            document.getElementById("div-perso").style.display = "none";
            document.getElementById("div-cordonnées").style.display = "none";
            document.getElementById("div-secu").style.display = "none";

            // Remove bg-red from all buttons
            document.getElementsByClassName("btn-perso")[0].classList.remove("bg-red");
            document.getElementsByClassName("btn-cord")[0].classList.remove("bg-red");
            document.getElementsByClassName("btn-secu")[0].classList.remove("bg-red");

            // Show the correct section and highlight the corresponding button
            if (type === "secu") {
                document.getElementById("div-secu").style.display = "block";
                document.getElementsByClassName("btn-secu")[0].classList.add("bg-red");
                updateUrl('secu');
            } else if (type === "cord") {
                document.getElementById("div-cordonnées").style.display = "block";
                document.getElementsByClassName("btn-cord")[0].classList.add("bg-red");
                updateUrl('cord');
            } else {
                document.getElementById("div-perso").style.display = "block";
                document.getElementsByClassName("btn-perso")[0].classList.add("bg-red");
                updateUrl('perso');
            }
        }

        // Function to update the URL without reloading the page
        function updateUrl(section) {
            const newUrl = `${window.location.protocol}//${window.location.host}${window.location.pathname}?section=${section}`;
            window.history.pushState({ path: newUrl }, '', newUrl);
        }

        // On page load, check the URL and show the correct section
        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const section = urlParams.get('section');

            if (section === 'secu') {
                change('secu');
            } else if (section === 'cord') {
                change('cord');
            } else {
                change('perso');
            }
        });
    </script>




@endsection
