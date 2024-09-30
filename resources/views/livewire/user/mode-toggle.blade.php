<li class="d-flex justify-content-between align-items-center">
    <span class="medium text-medium">En Voyage</span>
    <label class="switch" style="margin-right: 5px; margin-top: 10px;">
        <input type="checkbox" wire:click="toggleVoyageMode" {{ $isVoyageMode ? 'checked' : '' }}>
        <span class="slider round"></span>
    </label>
</li>
@if (session()->has('message'))
<div class="alert alert-success mt-2">
    {{ session('message') }}
</div>
@endif
