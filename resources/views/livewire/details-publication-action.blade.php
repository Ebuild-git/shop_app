<div>
    @if (session()->has('error'))
        <div class="alert alert-danger small">
            {{ session('error') }}
        </div>
        <hr>
    @enderror
    @if (session()->has('success'))
        <div class="alert alert-success small">
            {{ session('success') }}
        </div>
        <hr>
    @enderror
    @if ($post->verified_at != null)
        <div class="alert alert-light" role="alert">
            <i class="bi bi-check2-square"></i> &nbsp;
            Validé le {{ $post->verified_at }}
        </div>
    @endif
    <div class="btn-group " role="group" aria-label="Basic example" style="width: 100% !important;">
        @if ($post->verified_at == null)
            <button type="button" class="btn btn-success btn-block" wire:click="valider()">
                <i class="bi bi-check-circle"></i>
                &nbsp;
                Accepter
            </button>
        @endif
        <button type="button" class="btn btn-danger btn-block" wire:click=delete()>
            <i class="bi bi-x-lg"></i>
            &nbsp;
            Réfuser
        </button>
    </div>
</div>
