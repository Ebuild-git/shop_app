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

    @if ($post->verified_at )
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
        @if ($post->sell_at == null)
            <button type="button" class="btn btn-danger btn-block" wire:click="delete({{ $post->id }})">
                <i class="bi bi-x-lg"></i>
                &nbsp;
                supprimer
            </button>
        @endif
    </div>
    @if ($post->sell_at != null)
        <div class="alert alert-light">
            <div class="header">
                INFORMATION DE L'ACHETEUR
            </div>
            <div class="p-2">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <div class="d-flex align-items-start">
                            <div class="d-flex align-items-start">
                                <div class="avatar me-2">
                                    <img src="/assets-admin/img/avatars/2.png" alt="Avatar"
                                        class="rounded-circle">
                                </div>
                                <div class="me-2 ms-1">
                                    <h6 class="mb-0">
                                        {{ $post->acheteur->name }}
                                    </h6>
                                    <small class="text-muted">
                                        {{ $post->acheteur->GetPosts->count() }} publications. <br>
                                       <b> Numéro de Téléphone :</b> {{ $post->acheteur->phone_number ?? '/' }} <br>
                                       <b> Adresse :</b> {{ $post->acheteur->address ?? '/' }} <br>
                                    </small>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <button class="btn btn-label-primary btn-icon btn-sm waves-effect"
                                    onclick="document.location.href='/admin/client/{{ $post->acheteur->id }}/view'">
                                    <i class="ti ti-eye ti-xs"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="btn-group btn-block">
                @if ($post->statut != 'livré')
                    <button type="button" class="btn btn-primary btn-block" wire:click=mark_as_livrer()>
                        Marquer Livré !
                    </button>
                @endif
                <button type="button" class="btn btn-dark btn-block" wire:click=remettre()>
                    <i class="bi bi-x-lg"></i>
                    &nbsp;
                    Remettre a la vente
                </button>
            </div>
        </div>
    @endif
</div>
