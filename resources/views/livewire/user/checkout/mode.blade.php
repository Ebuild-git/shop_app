<div>
    <div class="container">
        <div class="text-center">
            <h3>
                <b class="color">Mode de paiement & livraison</b>
            </h3>
        </div>
        <br>
        <div class="row">
            <!-- Payment Info Section -->
            <div class="col-lg-8 col-md-7 col-sm-12 mx-auto mb-4">
                <div class="card p-4 modern-card">
                    <h5 class="mb-3 payment-title">
                        <b><i class="bi bi-credit-card-2-front"></i> Paiement à la livraison</b>
                    </h5>
                    <p class="payment-description">
                        Vous payez à la livraison (à l'adresse de votre choix) et recevez votre commande en toute sécurité.
                    </p>
                    <hr class="my-3">
                    <b class="delivery-title"><i class="bi bi-geo-alt"></i> Adresse de livraison</b>
                    <div class="address-card mt-3 p-3">
                        <p class="address-text">
                            <i class="bi bi-house-door"></i> {{ $user->nom_batiment }}, {{ $user->rue }},
                            {{ $user->etage }}{{ $user->etage ? ', ' : '' }}{{ $user->num_appartement }},
                            {{ $user->address }},  {{ $user->region_info->nom }}
                        </p>

                        <p class="region-text mb-1">
                            <b><i class="bi bi-geo"></i> {{ $user->region_info->nom }}</b>
                        </p>
                        <p class="phone-text">
                            <i class="bi bi-telephone"></i> {{ $user->phone_number }}
                        </p>
                    </div>
                </div>
            </div>
            <!-- Order Summary Section -->
            <div class="col-lg-4 col-md-5 col-sm-12 mx-auto">
                <div class="card p-4 summary-card">
                    <div class="d-flex justify-content-between summary-item mb-3">
                        <b>Total des articles :</b>
                        <span>{{ $nbre_article }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between summary-item">
                        <span>Sous-total :</span>
                        <b>{{ number_format($total, 2, '.', '') * $nbre_article }} <sup>DH</sup></b>
                    </div>
                    <div class="d-flex justify-content-between summary-item">
                        <span>Total de frais :</span>
                        <b>{{ $frais * $nbre_article }} <sup>DH</sup></b>
                    </div>
                    <div class="d-flex justify-content-between summary-item total-section">
                        <h4 class="color"><b>TOTAL :</b></h4>
                        <h4><b>{{ number_format($total, 2, '.', '') + $frais * $nbre_article }} <sup>DH</sup></b></h4>
                    </div>
                    @if ($total > 0)
                        <hr>
                        <div class="text-center terms-section">
                            <p class="terms-text">
                                <input type="checkbox" id="acceptCond" onclick="enableButtonOnCheck()">
                                En poursuivant votre commande, vous acceptez les
                                <span data-toggle="modal" data-target="#conditions" class="color cursor">
                                    <b>Conditions générales</b>
                                </span> de <b style="color: black">SHOP<span class="color">IN</span></b>.

                            </p>

                            <button type="button" class="btn-validate w-100 mt-2" id="validateCartButton" wire:click="confirm()" disabled>
                                <span wire:loading><x-Loading></x-Loading></span>
                                Valider mon panier
                            </button>

                            <div class="mt-3">
                                <a href="{{ route('checkout') }}?step=2" class="return-link">Retour aux adresses de livraison</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between responsive-btn-wrapper" style="position: relative; top: -40px;">
            <a href="{{ route('checkout') }}?step=2" class="btn btn-dark address-btn">
                <i class="bi bi-arrow-left"></i> Adresse de livraison
            </a>
        </div>

    </div>

    @section('script')
        <script>
            $(document).ready(function() {
                $("#show_conditions").click(function() {
                    ('#conditions').modal('show');
                });
            });
        </script>
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            const validateCartButton = document.getElementById('validateCartButton');
            const agreeConditionButton = document.getElementById('agreeConditionButton');

            // Check localStorage for conditionsAccepted
            if (localStorage.getItem('conditionsAccepted') === 'true') {
                validateCartButton.disabled = false;
            } else {
                validateCartButton.disabled = true;
            }

            // Handle condition agreement
            agreeConditionButton.addEventListener('click', function () {
                localStorage.setItem('conditionsAccepted', 'true');
                validateCartButton.disabled = false;
                $('#conditions').modal('hide'); // Close the modal
                window.location.href = '/checkout?step=3'; // Redirect to the specified path
            });
        });
    </script> --}}
        <script>
            function enableButtonOnCheck() {
                const checkbox = document.getElementById('acceptCond');
                const validateButton = document.getElementById('validateCartButton');
                validateButton.disabled = !checkbox.checked;
            }
        </script>
    @endsection

</div>
