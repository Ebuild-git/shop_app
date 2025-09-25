<form wire:submit.prevent="update">
    @include('components.alert-livewire')

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>{{ __('current_rib_number') }}</label>
                <span class="text-danger">*</span>
                <input type="text" class="form-control border-r shadow-none" @error('rib_number') is-invalid @enderror
                    wire:model="rib_number" required>
                @error('rib_number')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-6">
            <div>
                <label>{{ __('bank_name') }}</label>
                <span class="text-danger">*</span>
                <div class="form-group" style="position: relative;">
                    <input type="text" class="form-control border-r shadow-none" id="bank-name"
                        @error('bank_name') is-invalid @enderror wire:model="bank_name" required>
                </div>
                @error('bank_name')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-6">
            <div>
                <label>{{ __('account_holder_name') }}</label>
                <span class="text-danger">*</span>
                <div class="form-group" style="position: relative;">
                    <input type="text" class="form-control border-r shadow-none" id="titulaire-name"
                        @error('titulaire_name') is-invalid @enderror wire:model="titulaire_name"
                        required>
                </div>
                @error('titulaire_name')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label for="cin_img">{{ __('cin_image') }}</label>
                <span class="text-danger">*</span>
                <input type="file" class="form-control" id="cin_img" wire:model="cin_img" accept="image/*">
                <small class="form-text text-muted">
                    {{ __('cin_img_info') }}
                </small>
                @error('cin_img')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        @if ($existingCinImg)
            <div class="col-sm-12">
                <label class="form-label d-block">{{ __('current_cin_image') }}</label>
                <img src="{{ asset('storage/' . $existingCinImg) }}" alt="CIN Image" class="img-fluid rounded" style="max-height: 200px;">
            </div>
        @endif

    </div>
    <div class="modal-footer mt-3">
        <button type="submit" class="bg">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading></span>
            {{ __('save_bank_info_changes') }}
            <i class="bi bi-arrow-right-circle-fill"></i>
        </button>
    </div>


</form>
<script>
    window.translations = {
        titleThanks: "{{ __('title_thanks') }}",
        messageUpdated: "{{ __('message_updated') }}",
        messageNotification: "{{ __('message_notification') }}",
    };
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Livewire.on('refreshAlluser-information', () => {
            document.getElementById('cinModalLabel').innerText = window.translations.titleThanks;
            document.querySelector('#cinModal .modal-body').innerHTML = `
                <div>
                    ${window.translations.messageUpdated}<br>
                    ${window.translations.messageNotification}
                </div>
            `;
            const footer = document.querySelector('#cinModal .modal-footer');
            if (footer) footer.remove();
        });

        window.addEventListener('cin-updated', () => {
            $('#cinModal').modal('hide');
        });
    });
</script>
