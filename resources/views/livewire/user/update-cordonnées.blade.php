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
                @error('cin_img')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>
    <div class="modal-footer mt-3">
        <button type="submit" class="bg">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading></span>
            {{ __('save_bank_info_changes') }}
            <i class="bi bi-arrow-right-circle-fill"></i>
        </button>
    </div>


</form>
