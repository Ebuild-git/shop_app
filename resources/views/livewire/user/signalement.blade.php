<div>
    @if ($is_send)
        <div class="p-3 text-center">
            <img src="/icons/bouclier.svg" alt="icon alert" height="60" srcset="">
            <br>
            <b class="text-success">
                Merci pour votre signalement!
            </b>
            <p>
                Nous allons examiner cette annonce dans les plus brefs délais.
            </p>
        </div>
    @else
        <div class="text-center mb-4">
            <h1 class="m-0 ft-regular h5 text-danger">
                <i class="bi bi-exclamation-octagon"></i>
                Signaler l'annonce.
            </h1>
            <span class="text-muted">
                " {{ $post->titre }} "
            </span>
        </div>
        <form wire:submit="signaler">
            <b>Motif</b>
            <select required wire:model="type" class="form-control shadow-none">
                <option value="">Choisir un motif</option>
                <option value="Fraude">Fraude</option>
                <option value="Spam">Spam</option>
            </select>
            @error('type')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
            <b>Message</b>
            <textarea wire:model="message" class="form-control border-r shadow-none" rows="6"></textarea>
            @error('message')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
            <div class="modal-footer">
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
