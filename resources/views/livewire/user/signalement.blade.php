<div>
    @if ($is_send)
        <div class="p-4 text-center">
            <img src="/icons/bouclier.svg" alt="icon alert" class="icon-modern">
            <br>
            <b class="text-success text-modern">
                {{ __('Thank you for your report!')}}
            </b>
            <p class="text-muted text-modern">
                {{ __('We will review this ad as soon as possible.')}}
            </p>
        </div>
    @else
        <div class="text-center mb-4">
            <h1 class="m-0 ft-regular h5 text-danger">
                <i class="bi bi-exclamation-octagon"></i>
                {{ __('report_ad') }}
            </h1>
            <span class="text-muted">
                "
                <span class="color text-capitalize">
                    {{ \App\Traits\TranslateTrait::TranslateText($post->titre) }}
                </span>
                "
            </span>
        </div>
        <form wire:submit="signaler">
            <b>{{ __('reason') }}</b>
            <select required wire:model="type" class="form-control shadow-none">
                <option value="">{{ __('choose_reason') }}</option>
                <option value="Annonce de produits interdits ou illégaux">{{ __('ad_illegal_products') }}</option>
                <option value="Annonce multiple du même article">{{ __('multiple_ads_same_item') }}</option>
                <option value="Autres violations des politiques du site">{{ __('other_policy_violations') }}</option>
                <option value="Contenu inapproprié">{{ __('inappropriate_content') }}</option>
                <option value="Description trompeuse de l'état de l'article">{{ __('misleading_description') }}</option>
                <option value="Fraude ou activité suspecte">{{ __('fraud_suspicious_activity') }}</option>
                <option value="Information incorrecte sur la taille, la couleur, etc.">{{ __('incorrect_info') }}</option>
                <option value="Photos floues ou de mauvaise qualité">{{ __('blurry_photos') }}</option>
                <option value="Prix excessif pour le produit mis en vente">{{ __('excessive_price') }}</option>
                <option value="Produit contrefait ou non authentique">{{ __('counterfeit_product') }}</option>
                <option value="Publicité non autorisée ou spam">{{ __('unauthorized_advertisement') }}</option>
                <option value="Violation des droits d'auteur ou de la propriété intellectuelle">{{ __('copyright_violation') }}</option>
            </select>
            @error('type')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
            <b>{{ \App\Traits\TranslateTrait::TranslateText('Message') }}</b>
            <textarea wire:model="message" class="form-control border-r shadow-none" rows="6"
            placeholder="{{ __('message_placeholder') }}"
            oninput="updateCharCount(event)"
            ></textarea>
            @error('message')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
            <div class="modal-footer d-flex justify-content-between align-items-center">
                <span id="charCount" class="text-muted">
                    {{ strlen($message) }} {{ __('caractères') }}
                </span>
                <button type="submit" class="btn btn-sm btn-danger">
                    <span wire:loading>
                        <x-Loading></x-Loading>
                    </span>
                    {{ __('send_report') }}
                    <i class="bi bi-arrow-right-circle-fill"></i>
                </button>
            </div>

        </form>
    @endif
</div>
<script>
    function updateCharCount(event) {
        var characterLabel = @json(__('caractères'));
        const charCount = event.target.value.length;
        document.getElementById('charCount').textContent = charCount + ' ' + characterLabel;
    }
</script>
<script>
    let isSuccess = false;
    window.addEventListener('report-submitted', function() {
        isSuccess = true;
        setTimeout(function() {
            location.reload();
        }, 3000);
    });
    $('#signalModal_{{ $post->id }}').on('hide.bs.modal', function () {
        if (isSuccess) {
            location.reload();
        }
    });
</script>


