<div>
    @include('components.alert-livewire')
    <form wire:submit ="envoyer">
        <div class="alert alert-dark my-auto mb-3">
            <img width="24" height="24" src="https://img.icons8.com/external-tanah-basah-basic-outline-tanah-basah/24/FFFFFF/external-attache-file-tanah-basah-basic-outline-tanah-basah.png" alt="external-attache-file-tanah-basah-basic-outline-tanah-basah"/>
            {{ $titre }}
        </div>
        <table class="w-100">
            <tr>
                <td>
                    <label for="">Destinataire</label>
                </td>
                <td>
                    <input type="email" wire:model="recipientEmail" class="form-control @error('email') is-invalid @enderror " readonly>
                    @error('email')
                        <span class="small text-danger"> {{ $message }} </span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>
                    <label for="">Sujet</label>
                </td>
                <td>
                    <input type="text" wire:model="sujet" placeholder="Sujet du mail a envoyer" required
                        class="form-control @error('sujet') is-invalid @enderror" >
                    @error('sujet')
                        <span class="small text-danger"> {{ $message }} </span>
                    @enderror
                </td>
            </tr>
        </table>
        <hr>
        <div class="mb-2">
            <label for="">Message</label>
            <textarea class="form-control @error('message') is-invalid @enderror" wire:model="message" rows="10"></textarea>
            @error('message')
                <span class="small text-danger"> {{ $message }} </span>
            @enderror
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-sm btn-primary">
                <span wire:loading>
                    <x-loading></x-loading>
                </span>
                Envoyer le message
            </button>
        </div>
    </form>
</div>
