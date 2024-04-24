<div class="row">
    <div class="col-sm-8">
        <div class="d-flex justify-content-between">
            <div>
                Résultat : {{ count($logos)}}
            </div>
        <div class="text-end">
            <span wire:loading>
                <x-Loading></x-Loading>
            </span>
        </div>
    </div>
    <hr>
        <div class="p-2">
            <div class="row">
                @forelse ($logos as $logo_save)
                    <div class="col-sm-3 col-6 card-admin-partenaires-logo  pb-2">
                        <button class="btn btn-sm btn-danger" wire:confirm="Voulez-vous supprimer ?"
                            wire:click="delete('{{ $logo_save }}')">
                            <i class="bi bi-trash3"></i>
                        </button>
                        <div class="list-logo">
                            <div class="logo">
                                <img src="{{ Storage::url($logo_save) }}" alt="logo" srcset="">
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="col-sm-12 p-5 text-center">
                        <img width="50" height="50"
                            src="https://img.icons8.com/ios/50/008080/not-showing-video-frames.png"
                            alt="not-showing-video-frames" />
                        <div>
                            Aucun logo disponible pour l'instant;
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <form wire:submit="create">
            <h5>
                Enregistrement d'un partenaire
            </h5>
            <div class="mb-3">
                <label for="">
                    Image du logo
                </label>
                <input type="file" required class="form-control" placeholder="Recherche d'un logo" wire:model="logo">
                @error('logo')
                    <span class="small text-danger">{{ $message }}</span>
                @enderror
                <div class="preview-image-partenaire">
                    @if ($logo)
                        <img src="{{ $logo->temporaryUrl() }}">
                    @else
                        <img src="/icons/no-image.jpg" alt="" srcset="">
                    @endif
                </div>
                <br>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Enregistrer
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
