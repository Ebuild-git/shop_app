<form wire:submit.prevent="update">
    @include('components.alert-livewire')

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>{{ __('current_rib_number') }}</label>
                <input
                        type="text"
                        class="form-control border-r shadow-none @error('rib_number') is-invalid @enderror"
                        wire:model="rib_number"
                        inputmode="numeric"
                        maxlength="24"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 24)"
                    >
                @error('rib_number')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-6">
            <div>
                <label>{{ __('bank_name') }}</label>
                <div class="form-group" style="position: relative;">
                    <input type="text" class="form-control border-r shadow-none" id="bank-name"
                        @error('bank_name') is-invalid @enderror wire:model="bank_name">
                </div>
                @error('bank_name')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-6">
            <div>
                <label>{{ __('account_holder_name') }}</label>
                <div class="form-group" style="position: relative;">
                    <input type="text" class="form-control border-r shadow-none" id="titulaire-name"
                        @error('titulaire_name') is-invalid @enderror wire:model="titulaire_name"
                        >
                </div>
                @error('titulaire_name')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="cin_img">{{ __('cin_image') }} <small class="text-muted">({{ __('recto') }})</small></label>
                <span class="text-danger">*</span>
                <input type="file" class="form-control" id="cin_img" wire:model="cin_img" accept="image/*">
                <small class="form-text text-muted">
                    {{ __('cin_img_info') }}
                </small>
                @error('cin_img')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            @if ($cin_img)
                <div class="mt-2">
                    <img src="{{ $cin_img->temporaryUrl() }}" alt="CIN Recto Preview" class="img-fluid rounded" style="max-height: 200px;">
                </div>
            @elseif ($existingCinImg)
                <div class="mt-2">
                    <label class="form-label d-block">{{ __('current_cin_image') }}</label>
                    <img src="{{ asset('storage/' . $existingCinImg) }}" alt="CIN Recto" class="img-fluid rounded" style="max-height: 200px;">
                </div>
            @endif
        </div>

        {{-- CIN Verso --}}
        <div class="col-sm-6">
            <div class="form-group">
                <label for="cin_img2">{{ __('cin_image') }} <small class="text-muted">({{ __('verso') }})</small></label>
                <span class="text-danger">*</span>
                <input type="file" class="form-control" id="cin_img2" wire:model="cin_img2" accept="image/*">
                <small class="form-text text-muted">
                    {{ __('cin_img_info') }}
                </small>
                @error('cin_img2')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            @if ($cin_img2)
                <div class="mt-2">
                    <img src="{{ $cin_img2->temporaryUrl() }}" alt="CIN Verso Preview" class="img-fluid rounded" style="max-height: 200px;">
                </div>
            @elseif ($existingCinImg2)
                <div class="mt-2">
                    <label class="form-label d-block">{{ __('current_cin_image') }}</label>
                    <img src="{{ asset('storage/' . $existingCinImg2) }}" alt="CIN Verso" class="img-fluid rounded" style="max-height: 200px;">
                </div>
            @endif
        </div>

    </div>
    <div class="modal-footer mt-3">
        <button type="submit" class="bg">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading></span>
            {{-- {{ __('save_bank_info_changes') }} --}}
            {{ $hasExistingInfo ? __('modifier') : __('sauvegarder') }}
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
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('redirect-home2', () => {
            setTimeout(() => {
                window.location.href = "{{ route('home') }}";
            }, 2500);
        });
    });
</script>
