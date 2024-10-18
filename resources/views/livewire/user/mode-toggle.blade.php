<li class="mode-toggle">
    <span class="medium text-medium">Mode voyage</span>
    <label class="switch" style="margin-right: 5px; margin-top: 10px;">
        <input type="checkbox" wire:click="toggleVoyageMode" {{ $isVoyageMode ? 'checked' : '' }}>
        <span class="slider round"></span>
    </label>
</li>



{{-- class="d-flex justify-content-between align-items-center" --}}
