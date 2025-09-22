<div>
    <div class="container">
        <div class="text-center">
            <h3>
                <b class="color">{{ __('payment_shipping')}}</b>
            </h3>
        </div>
        <br>
        <div class="row" style="{{ app()->getLocale() == 'ar' ? 'text-align: right; direction: rtl;' : 'text-align: left; direction: ltr;' }}">
            <!-- Payment Info Section -->
            <div class="col-lg-8 col-md-7 col-sm-12 mx-auto mb-4">
                <div class="card p-4 modern-card">
                    <h5 class="mb-3 payment-title">
                        <b><i class="bi bi-credit-card-2-front"></i> {{ __('cash_on_delivery') }}</b>
                    </h5>
                    <p class="payment-description">
                        {{ __('cash_on_delivery_info') }}
                    </p>
                    <hr class="my-3">
                    <b class="delivery-title"><i class="bi bi-geo-alt"></i> {{ __('Adresse de livraison') }}</b>
                    <div class="address-card mt-3 p-3">
                        <p class="address-text">
                            <i class="bi bi-house-door"></i>
                            {{ $user->rue ? $user->rue . ',' : '' }}
                            {{ $user->nom_batiment ? $user->nom_batiment . ',' : '' }}
                            {{ $user->etage ? $user->etage . ',' : '' }}
                            {{ $user->num_appartement ? $user->num_appartement . ',' : '' }}
                            {{ $user->address ? $user->address . ',' : '' }}
                            {{ optional($user->region_info)->nom ? $user->region_info->nom : '' }}
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
                        <b>{{ __('total_articles') }} :</b>
                        <span>{{ $nbre_article }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between summary-item">
                        <span>{{ __('subtotal')}} :</span>
                        <b>{{ number_format($total, 2, '.', '') }} <sup>{{ __('currency') }}</sup></b>
                    </div>
                    <div class="d-flex justify-content-between summary-item">
                        <span>{{ __('total_fees')}} :</span>
                        <b>{{ number_format($totalDeliveryFees, 2, '.', '') }} <sup>{{ __('currency') }}</sup></b>
                    </div>
                    <div class="d-flex justify-content-between summary-item total-section">
                        <h4 class="color"><b>{{ __('total') }} :</b></h4>
                        <h4><b>{{ number_format($totalWithDelivery, 2, '.', '') }} <sup>{{ __('currency') }}</sup></b></h4>
                    </div>
                    @if ($total > 0)
                        <hr>
                        <div class="text-center terms-section">
                            <p class="terms-text">
                                <input type="checkbox" id="acceptCond" onclick="enableButtonOnCheck()">
                                {!! __('terms_notice') !!}
                            </p>

                            <button type="button" class="btn-validate w-100 mt-2" id="validateCartButton" wire:click="confirm()" disabled>
                                <span wire:loading><x-Loading></x-Loading></span>
                                {{ __('validate_cart') }}
                            </button>

                            <div class="mt-3">
                                <a href="{{ route('checkout') }}?step=2" class="return-link">{{ __('back_to_addresses') }}</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="d-flex justify-content-between responsive-btn-wrapper" style="position: relative; top: -75px;">
                <a href="{{ route('checkout') }}?step=2" class="address-btn">
                    @if (app()->getLocale() == 'ar')
                        {{ __('Adresse de livraison') }}
                        <i class="bi bi-arrow-left"></i>
                    @else
                        <i class="bi bi-arrow-left"></i>
                        {{ __('Adresse de livraison') }}
                    @endif
                </a>

            </div>
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

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const checkbox = document.getElementById('acceptCond');
                const validateButton = document.getElementById('validateCartButton');

                checkbox.classList.add("soft-pulse-effect");

                checkbox.addEventListener("click", function() {
                    validateButton.disabled = !checkbox.checked;
                    if (checkbox.checked) {
                        checkbox.classList.remove("soft-pulse-effect");
                    }
                });
            });
        </script>


    @endsection

</div>
