<div>
    @if ($is_send)
        <div class="p-4 text-center">
            <img src="/icons/bouclier.svg" alt="icon alert" class="icon-modern">
            <br>
            <b class="text-success text-modern">
                Merci pour votre signalement!
            </b>
            <p class="text-muted text-modern">
                Nous allons examiner cette annonce dans les plus brefs délais.
            </p>
        </div>
    @else
        <div class="text-center mb-4">
            <h1 class="m-0 ft-regular h5 text-danger">
                <i class="bi bi-exclamation-octagon"></i>
                Signaler cet annonce
            </h1>
            <span class="text-muted">
                "
                <span class="color text-capitalize">
                    {{ $post->titre }}
                </span>
                "
            </span>
        </div>
        <form wire:submit="signaler">
            <b>Motif</b>
            <select required wire:model="type" class="form-control shadow-none">
                <option value="">Choisir un motif</option>
                <option value="Annonce de produits interdits ou illégaux">Annonce de produits interdits ou illégaux</option>
                <option value="Annonce multiple du même article">Annonce multiple du même article</option>
                <option value="Autres violations des politiques du site">Autres violations des politiques du site</option>
                <option value="Contenu inapproprié">Contenu inapproprié</option>
                <option value="Description trompeuse de l'état de l'article">Description trompeuse de l'état de l'article</option>
                <option value="Fraude ou activité suspecte">Fraude ou activité suspecte</option>
                <option value="Information incorrecte sur la taille, la couleur, etc.">Information incorrecte sur la taille, la couleur, etc.</option>
                <option value="Photos floues ou de mauvaise qualité">Photos floues ou de mauvaise qualité</option>
                <option value="Prix excessif pour le produit mis en vente">Prix excessif pour le produit mis en vente</option>
                <option value="Produit contrefait ou non authentique">Produit contrefait ou non authentique</option>
                <option value="Publicité non autorisée ou spam">Publicité non autorisée ou spam</option>
                <option value="Violation des droits d'auteur ou de la propriété intellectuelle">Violation des droits d'auteur ou de la propriété intellectuelle</option>
            </select>
            @error('type')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
            <b>Message</b>
            <textarea wire:model="message" class="form-control border-r shadow-none" rows="6"
            placeholder="Veuillez entrer un message expliquant clairement la raison de votre signalement. Votre message doit contenir au moins 20 caractères."
            oninput="updateCharCount(event)"
            ></textarea>
            @error('message')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
            <div class="modal-footer d-flex justify-content-between align-items-center">
                <span id="charCount" class="text-muted">
                    {{ strlen($message) }} caractères
                </span>
                <button type="submit" class="btn btn-sm btn-danger">
                    <span wire:loading>
                        <x-Loading></x-Loading>
                    </span>
                    Envoyer le signalement
                    <i class="bi bi-arrow-right-circle-fill"></i>
                </button>
            </div>

        </form>
    @endif
</div>
<script>
    function updateCharCount(event) {
        const charCount = event.target.value.length;
        document.getElementById('charCount').textContent = charCount + ' caractères';
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


