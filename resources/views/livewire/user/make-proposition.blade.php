<form wire:submit="proposer">
    @if (session()->has('error'))
        <div class="alert alert-danger small text-center">
            {{ session('error') }}
        </div>
        <br>
    @enderror
    @if (session()->has('info'))
        <div class="alert alert-info small text-center">
            {{ session('info') }}
        </div>
        <br>
    @enderror
    @if (session()->has('success'))
        <div class="alert alert-success small text-center">
            {{ session('success') }}
        </div>
        <br>
    @enderror

    <b class="text-danger">
        Avant de commnader !
    </b>
    <p>
        Nous souhaitons vous informer que dès que vous soumettez une demande pour commander un article, le
        processus de livraison ne démarre pas immédiatement.
    </p>
    <p>
        Après avoir fait votre demande, nous transmettons votre commande au vendeur concerné. Ce dernier
        aura besoin de temps pour examiner votre demande et y répondre. Une fois que le vendeur a répondu et
        confirmé votre commande, le processus de livraison sera enclenché.
    </p>
    <br>
    <div class="small">
        <input type="checkbox" id="checkbox">
        <b>J'ai compris !</b>
    </div>
    <br><br>
    <div>

        <div class="modal-footer">
            <button type="submit" class="btn bg-red" id="commander-btn" disabled>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                    wire:loading></span>
                Envoyer ma proposition
                <i class="bi bi-arrow-right-circle-fill"></i>
            </button>
        </div>
    </div>

    <script>
        $("#checkbox").on("change", function() {
            if ($(this).is(":checked")) {
                $('#commander-btn').prop("disabled", false);
            } else {
                $('#commander-btn').prop("disabled", true);
            }
        });
    </script>

</form>
