@extends('User.fixe')
@section('titre', "Finaliser l'achat")
@section('body')

    <div class="bg-red @if ($step == 4) d-none @endif">
        <div class="container">
            <fiv class="row">
                <div class="col-sm-10 mx-auto p-3">
                    <div class="d-flex justify-content-around timeline">
                        <a href="{{ route('checkout') }}?step=1"
                            class="step @if ($step == 1) active @endif ">
                            <i class="bi bi-cart"></i>
                            Panier
                        </a>
                        <a href="{{ route('checkout') }}?step=2" class="step @if ($step == 2) active @endif">
                            <i class="bi bi-geo-alt"></i>
                            Adresse de livraison
                        </a>
                        <a href="#"
                            class="step @if ($step == 3) active @endif">
                            <i class="bi bi-wallet2"></i>
                            Paiement
                        </a>
                    </div>
                </div>
            </fiv>
        </div>
    </div>

    <div class="container pt-5 pb-5">
        @if ($step == 1)
            @livewire('User.Checkout.Panier')
        @endif

        @if ($step == 2)
            @livewire('User.Checkout.Adresse')
        @endif

        @if ($step == 3)
            @livewire('User.Checkout.Mode')
        @endif

        @if ($step == 4)
            <div class="mt-5 mb-5">
                <div class="text-center">
                    <h2 class="color">
                        <img src="/icons/icons8-coche.gif" alt="" srcset=""> <br>
                        Merci pour votre commande
                    </h2>
                    <p>
                        Vous allez recevoir un email de confirmation conprenent un recapitulatif de votre commande.
                    </p>
                    <a href="/" class="btn btn-outline-dark">
                        <i class="bi bi-house-door-fill"></i>
                        Retour à la page d'accueil
                    </a>
                </div>
            </div>
        @endif

    </div>




    <style>
        .timeline .step {
            cursor: pointer;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .timeline .step:hover {
            border: solid 1px white;
        }

        .timeline .active {
            font-weight: bold;
            border: solid 1px white;
            color: #018d8d;
            background-color: white;

        }

        .btn-black {
        background-color: black;
        color: white;
        border: none;
        }

        .btn-black:hover {
            background-color: rgb(15, 15, 15);
        }
        .form-control:focus {
        border-color: black;
        box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.25);
    }

    .custom-select {
        appearance: none;
        background-color: #fff;
        border: 1px solid black;
        border-radius: 4px;
        padding: 10px;
        box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.25);
        transition: box-shadow 0.3s ease;
        color: rgb(92, 82, 82);
    }

    .custom-select:focus {
        border-color: black;
        outline: none;
        box-shadow: 0 0 8px rgba(0,0,0,0.2);
    }
    .custom-select::-ms-expand {
        display: none;
    }
    .form-grid .grid-container {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    padding: 5px;
    }

    .form-grid .form-group {
        display: flex;
        flex-direction: column;
    }

    .add-new {
        background-color: black;
        border: none;
        border-radius: 20px;
        padding: 12px 12px;
        transition: background-color 0.3s;
        color: white;
    }

    .add-new:hover {
        background-color: #e2e3e5;
    }

    .custom-default {
    color: black;
    border: 1px solid black;
    background-color: transparent;
    }

    .custom-default:hover {
        background-color: darkcyan;
        color: #000000;
    }

.custom-edit {
    color: black;
    border: 1px solid #6d6e6e;
    background-color: transparent;
}

.custom-delete {
    color: #dc3545;
    border: 1px solid #dc3545;
    background-color: transparent;
}

.custom-delete:hover {
    background-color: #f8d7da;
    color: #c82333; }
    </style>

@endsection
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
function closeLocationModal() {
    $('#location-modal').modal('hide');
}

</script>
<script>
function populateModal(button) {
    // Retrieve data from data attributes
    const region = button.getAttribute('data-region');
    const city = button.getAttribute('data-city');
    const street = button.getAttribute('data-street');
    const building = button.getAttribute('data-building');
    const floor = button.getAttribute('data-floor');
    const apartment = button.getAttribute('data-apartment');
    const phone = button.getAttribute('data-phone');

    // Populate the modal inputs
    document.getElementById('extraRegion').value = region;
    document.getElementById('extraCity').value = city;
    document.getElementById('extraStreet').value = street;
    document.getElementById('extraBuilding').value = building;
    document.getElementById('extraFloor').value = floor;
    document.getElementById('extraApartment').value = apartment;
    document.getElementById('extraPhoneNumber').value = phone;
}

</script>
