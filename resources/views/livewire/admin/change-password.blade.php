<form wire:submit="change_password">
    <div class="row">
        <div class="mb-3 col-md-6 form-password-toggle">
            <label class="form-label" for="currentPassword">Current Password</label>
            <div class="input-group input-group-merge">
                <input class="form-control" type="password" wire:model="curent" name="currentPassword" id="currentPassword"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
            </div>
            @error('curent')
                <small class="text-danger small">{{ $message }}</small>
            @enderror
        </div>
    </div>
    <div class="row">

        <div class="mb-3 col-md-6 form-password-toggle">
            <label class="form-label" for="newPassword">New Password</label>
            <div class="input-group input-group-merge">
                <input class="form-control" type="password" wire:model="password" id="newPassword" name="newPassword"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
            </div>
            @error('password')
                <small class="text-danger small">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3 col-md-6 form-password-toggle">
            <label class="form-label" for="confirmPassword">Confirm New Password</label>
            <div class="input-group input-group-merge">
                <input class="form-control" type="password" wire:model="password_confirmation" name="confirmPassword"
                    id="confirmPassword"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
            </div>
            @error('password_confirmation')
                <small class="text-danger small">{{ $message }}</small>
            @enderror
        </div>
        <div class="col-12 mb-4">
            <h6>Password Requirements:</h6>
            <ul class="ps-3 mb-0">
                <li class="mb-1">Minimum 8 characters long - the more, the better</li>
                <li class="mb-1">At least one lowercase character</li>
                <li>At least one number, symbol, or whitespace character</li>
            </ul>
        </div>
        <div>
            <button type="submit" class="btn btn-primary me-2">Save changes</button>
            <button type="reset" class="btn btn-label-secondary">Cancel</button>
        </div>
    </div>
</form>
